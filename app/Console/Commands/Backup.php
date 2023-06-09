<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{ Storage, DB, Mail };
use Illuminate\Mail\Message;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:backup {--t|type=full} {--db-source=table} {--s|silent}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lancia un backup';


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $silent = $this->option('silent');
        $backup_folder = env('BACKUP_DESTINATION_FOLDER');
        $aws_bucket = env('AWS_BUCKET');

        if(empty($backup_folder) || empty($aws_bucket)) {
            $this->error("Please fill all paths, and aws parameters. Aborting.");
            return;
        }

        if(!Storage::disk('local')->exists($backup_folder)) {
            Storage::disk('local')->makeDirectory("$backup_folder");
            $this->error("Backup folder created.");
        }

        $this->line(date('Y-m-d h:i').': starting operation...');
        $type = $this->option('type');

        if($type==='full' || $type==='database')
        {
            $db_host = env('DB_HOST');
            $login_path = env('DB_LOGIN_PATH');
            $excluded_dbs = ['information_schema', 'mysql', 'performance_schema', 'sys'];
            $mysql = exec('which mysql'); // Linux bin paths, modify if it can't be autodetected via which command
            $dump_command = "--login-path=$login_path --no-tablespaces";
            $temp_destination_path =  Storage::disk('local')->path("$backup_folder/tmp");
            $db_list = null;
            $exported_dbs = 0;
            $archive_file_name = env('BACKUP_PREFIX').'_db_'.date('Y-m-d').'.tar.gz';

            if(empty($login_path)) {
                $this->error("Please fill all paths, and aws parameters. Aborting.");
                return;
            }

            // se non c'è creo un cartella temporanea per metterci i db da zippare
            if(!Storage::disk('local')->exists("$backup_folder/tmp")) {
                Storage::disk('local')->makeDirectory("$backup_folder/tmp");
            }

            if($this->option('db-source')!=='table')
            {
                exec("$mysql --login-path=$login_path -h $db_host -Bse 'show databases'", $db_list);
                
                if(!is_null($db_list) && is_array($db_list) && count($db_list)) {
                    if(!$silent) $this->line("Start db dump...");

                    //foreach($db_list as $db_name) {
                    $this->withProgressBar($db_list, function($db_name) use($dump_command, $excluded_dbs, $db_host, $temp_destination_path, &$exported_dbs) {
                        if(!in_array($db_name, $excluded_dbs)) {
                            exec("mysqldump $dump_command -h $db_host $db_name > $temp_destination_path/$db_name.sql");
                            $exported_dbs++;
                        }
                    });
                    if(!$silent) {
                        $this->newLine();
                        $this->line("Db dump complete. $exported_dbs exported.");
                    }

                    // at least one db to export: create zip archive and delete tmp folder
                    if($exported_dbs > 0) {
                        $dbFileZip = $this->zipDbFolder($backup_folder, $temp_destination_path, $archive_file_name);
                        $archive_name_with_path = Storage::disk('local')->path($dbFileZip);

                        if(!$silent) $this->line("Database archive created and temp folder cleared.");

                        if(!$silent) $this->line("Begin S3 transfer...");
                        exec("aws s3 cp $archive_name_with_path $aws_bucket/$archive_file_name --quiet --storage-class=STANDARD_IA");
                        //exec('cp '.Storage::disk('local')->path($dbFileZip).' ~/www/'); // ***debug***
                        
                        Storage::disk('local')->delete($dbFileZip);

                    } else {
                        $this->line('No db to export.');
                    } 
                }

            } else {
                $db_list = DB::table('databases')->where('active', 1)->get();
                if(!$db_list->count()) {
                    $this->line('No db to export.');
                    return;
                }

                if(!$silent) {
                    $this->withProgressBar($db_list, function($db) use($dump_command, $db_host, $temp_destination_path, &$exported_dbs) {
                        $local_dump_command = $dump_command;
                        if(!is_null($db->extra_commands)) $local_dump_command .= ' '.$db->extra_commands;
                        exec("mysqldump $local_dump_command -h $db_host $db->name > $temp_destination_path/$db->name.sql");
                        $exported_dbs++;
                    });
                    $this->newLine();
                } else {
                    foreach($db_list as $db) {
                        $local_dump_command = $dump_command;
                        if(!is_null($db->extra_commands)) $local_dump_command .= ' '.$db->extra_commands;
                        exec("mysqldump $local_dump_command -h $db_host $db->name > $temp_destination_path/$db->name.sql");
                        $exported_dbs++;
                    }
                }

                $this->line("Db dump complete. $exported_dbs items exported.");

                // at least one db exported: create zip archive and delete tmp folder
                $dbFileZip = $this->zipDbFolder($backup_folder, $temp_destination_path, $archive_file_name);
                
                if(!$silent) $this->line("Database archive created and temp folder cleared.");

                $archive_name_with_path = Storage::disk('local')->path($dbFileZip);
                
                exec("aws s3 cp $archive_name_with_path $aws_bucket/$archive_file_name --quiet --storage-class=STANDARD_IA");
                //exec('cp '.$archive_name_with_path.' ~/www/'); // ***debug***
                $this->line("DB transfer to S3 complete...\n");
                
                Storage::disk('local')->delete($dbFileZip);
            }
        }

        if($type==='full' || $type==='file')
        {
            $source_path = env('BACKUP_SOURCE_PATH');
            $archive_name = env('BACKUP_PREFIX').'_'.date('Y-m-d').'.tar.gz';
            $archive_full_path = Storage::disk('local')->path("$backup_folder/$archive_name");

            //$source_path = Storage::disk('local')->path($backup_folder);

            $exclude_list = resource_path('backup-assets/exclude-list.txt');
            //$exclude = '{"**/cache/*","**/vendor/*","Quantum","**/node_modules","**/storage/app/public/*","**/public/storage","**/.git","Thumb.db"}';

            if(!$silent) $this->line("Building Zip archive from $source_path...");
            //exec("tar -czf $archive_name_with_path --exclude-vcs -X ./exclude-list.txt -C $source_path . -C $backup_folder $archive_name");
            //exec("tar -czf $archive_full_path --exclude-vcs --exclude=$exclude -C $source_path .");
            exec("tar -czf $archive_full_path --exclude-vcs -X $exclude_list -C $source_path .");

            if(!$silent) $this->line("Zip archive $archive_name created.");

            if(!Storage::disk('local')->exists("$backup_folder/$archive_name")) {
                $this->error("Archive $archive_name not found. Operation aborted.");
            }

            if(!$silent) $this->line('Begin S3 transfer...');
            exec("aws s3 cp $archive_full_path $aws_bucket/$archive_name --quiet --storage-class=STANDARD_IA");

            // pulizia cartella
            Storage::disk('local')->delete("$backup_folder/$archive_name");
            //exec("cp $archive_full_path ~/www/"); // TODO ***debug***
        }

        $this->line(date('Y-m-d h:i').': operation complete.');

        Mail::raw('Backup succesfully completed', function (Message $message) {
            $message->to(env('MAIL_TO_ADDRESS'))
                ->from(env('MAIL_FROM_ADDRESS'))
                ->subject(env('APP_NAME').' - Backup complete.');
        });
    }


    private function zipDbFolder($backup_folder, $source_folder, $package_file_name)
    {
        $relative_archive_path = "$backup_folder/$package_file_name";
        $dest = Storage::disk('local')->path($relative_archive_path);
        
        $absolute_backup_folder = Storage::disk('local')->path($backup_folder);
        exec("tar -zcf $dest -C $absolute_backup_folder/tmp .");

        Storage::disk('local')->deleteDirectory("$backup_folder/tmp");// delete file
        
        return $relative_archive_path;
    }
}
