<script setup>
    import { ref, onMounted } from 'vue';
    import moment from 'moment';
    import 'moment/dist/locale/it';
    import { TransitionRoot } from '@headlessui/vue'
    import { CalendarIcon, ChevronLeftIcon, ChevronRightIcon, XMarkIcon } from '@heroicons/vue/20/solid';

    const props = defineProps({
        name: String,
        format: { type: String, default: 'DD/MM/YYYY' },
        placeholder: String,
        modelValue: { String, default: null },
        nullable: { Boolean, default: true },
        error: String
    });
    let emit = defineEmits(['update:modelValue']);

    // la data che corriposnde a oggi rimane sempre la stess: mi serve per fare i confronti con le altre
    let dataDiOggi = moment(new Date()).locale('it');
    // la data iniziale su cui fare le operazioni. Se non passo niente nella prop è quella di oggi. Poi eventualmente cambia con avanti/indietro
    let data = null;
    let days = ref([]);
    let monthName = ref(null);
    let displayDate = ref('');
    let show = ref(false);

    function createInterval(monthToAdd) {
        // so ho cliccato su avanti/indietro 
        if(monthToAdd!==0) {
            data.add(monthToAdd, 'months');
        }
        // mi creo una copia della data di partenza (faccio sempre partire dal primo del mese per evitare menate) sulla quale fare le varie operazioni.
        let dt = data.clone().startOf('month');
        // nome testuale del mese
        monthName.value = dt.format('MMMM')+' '+dt.year();
        // prima data: è il primo giorno della prima settimana del calendario, 
        let startDate = dt.startOf('isoWeek');

        // array dei giorni: la prima data la aggiungo a mano perchè non non va aggiunto niente. Parto da un array di appoggio per poi assegnarlo alla prop.
        let arrDays = [{
            dayNum: startDate.format('DD').replace(/^0/, ''),
            iso: startDate.toISOString(),
            //date: startDate.format('YYYY-MM-DD'),
            dateForHumans: startDate.format(props.format),
            isCurrentMonth: data.isSame(dt, 'month'),
            isToday: startDate.isSame(dataDiOggi, 'day'),
            isSelected: false// TODO per ora non gestito
        }];
        // aggiungo tutti gli altri
        for(let i=1; i <= 41; i++) {
            let current = startDate.add(1, 'days');
            arrDays.push({
                dayNum: current.format('DD').replace(/^0/, ''),
                iso: current.toISOString(),
                //date: current.format('YYYY-MM-DD'),
                dateForHumans: current.format(props.format),
                isCurrentMonth: data.isSame(dt, 'month'),
                isToday: current.isSame(dataDiOggi, 'day'),
                isSelected: false// TODO per ora non gestito
            });
        }
        days.value = arrDays;
    }

    function select(day) {
        show.value = false;
        displayDate.value = day.dateForHumans;
        emit('update:modelValue', day.iso);
    }

    function clear() {
        displayDate.value = 'Non impostata';
        if(show.value) show.value = false;
        emit('update:modelValue', null);
    }

    onMounted(() => {
        moment.locale('it');
        let tmp = props.modelValue===null ? new Date() : props.modelValue
        data = moment(tmp);
        displayDate.value = props.modelValue===null ? 'Non impostata' : data.format(props.format);
        
        createInterval(0);
    });
</script>

<template>
    <div class="relative mt-1 rounded-md shadow-sm">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <CalendarIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
        </div>
        <input type="text" :id="name" :value="displayDate" :placeholder="placeholder" @click="show=!show" readonly maxlength="10" :aria-invalid="error?'true':'false'" :class="[error ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'pl-10 border-gray-300 focus:border-purple-500 focus:ring-purple-500', 'text-gray-700 bg-gray-100 block w-full rounded-md pr-10 focus:outline-none sm:text-sm']" />
        <div v-show="modelValue!==null" class="absolute inset-y-0 right-0 flex items-center pr-3">
            <button type="button" v-if="props.nullable" @click="clear">
                <XMarkIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </button>
        </div>
    </div>
    <TransitionRoot :show="show" enter="transition-opacity duration-75" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity duration-150" leave-from="opacity-100" leave-to="opacity-0">
    <div class="absolute z-50 top-12 w-full flex justify-end">
        <div class="text-center lg:col-start-8 lg:col-end-13 lg:row-start-1 p-5 xl:col-start-9 min-w-full xl:min-w-[272px] xl:max-w-full bg-white rounded-lg shadow border border-gray-200">
            <div class="flex items-center text-gray-900">
                <button type="button" @click="createInterval(-1)" class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Mese precedente</span>
                    <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                </button>
                <div class="flex-auto font-semibold text-gray-700">{{ monthName }}</div>
                <button type="button" @click="createInterval(1)" class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Prossimo mese</span>
                    <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                </button>
            </div>
            <div class="mt-4 grid grid-cols-7 text-xs leading-6 text-gray-500">
                <div>L</div>
                <div>M</div>
                <div>M</div>
                <div>G</div>
                <div>V</div>
                <div>S</div>
                <div>D</div>
            </div>
            <div class="isolate mt-2 grid grid-cols-7 gap-px rounded-lg bg-gray-200 text-sm shadow ring-1 ring-gray-200">
                <button v-for="(day, dayIdx) in days" :key="dayIdx" type="button" @click="select(day)" :class="['py-1 hover:bg-gray-100 focus:z-10', day.isCurrentMonth ? 'bg-white' : 'bg-gray-50', day.isToday && 'font-semibold text-purple-600', day.isCurrentMonth && !day.isToday && 'text-gray-700', !day.isCurrentMonth && !day.isToday && 'text-gray-400', dayIdx === 0 && 'rounded-tl-lg', dayIdx === 6 && 'rounded-tr-lg', dayIdx === days.length - 7 && 'rounded-bl-lg', dayIdx === days.length - 1 && 'rounded-br-lg']">
                    <time :datetime="day.iso" :class="['mx-auto flex h-7 w-7 items-center justify-center rounded-full', day.isSelected && day.isToday && 'bg-purple-600', day.isSelected && !day.isToday && 'bg-gray-900']">{{ day.dayNum }}</time>
                </button>
            </div>
        </div> 
    </div>
    </TransitionRoot>
</template>