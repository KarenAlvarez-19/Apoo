<template>
<div class="page-wrapper">
    <div class="container-fluid">
        <v-sheet height="64">
            <v-toolbar flat color="white">
                <v-btn outlined class="mr-4" @click="setToday">
                    Hoy
                </v-btn>
                <v-btn fab text small @click="prev">
                    <v-icon small>mdi-chevron-left</v-icon>
                </v-btn>
                <v-btn fab text small @click="next">
                    <v-icon small>mdi-chevron-right</v-icon>
                </v-btn>
                <v-toolbar-title>{{ title }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn outlined class="mr-4" @click="dialog = true">
                    Agregar evento
                </v-btn>
                <v-menu bottom right>
                    <template v-slot:activator="{ on }">
                        <v-btn outlined v-on="on">
                            <span>{{ typeToLabel[type] }}</span>
                            <v-icon right>mdi-menu-down</v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="type = 'day'">
                            <v-list-item-title>Día</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = 'week'">
                            <v-list-item-title>Semana</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = 'month'">
                            <v-list-item-title>Mes</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = '4day'">
                            <v-list-item-title>4 días</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-toolbar>
        </v-sheet>
        <v-sheet height="600">
            <v-calendar ref="calendar" v-model="focus" color="primary" :events="events" :event-color="getEventColor" :event-margin-bottom="3" :now="today" :type="type" @click:event="showEvent" @click:more="viewDay" @click:date="viewDay" @change="updateRange" :weekdays="[1, 2, 3, 4, 5, 6, 0]" :short-weekdays="false"></v-calendar>
            <v-dialog v-model="dialog" max-width="520px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Agregar evento</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-form @submit.prevent="addEvent">
                                <v-row>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field type="text" label="Agregar Nombre" v-model="name"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field type="text" label="Agregar un Detalle" v-model="details"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field v-model="start" type="date" label="Inicio del evento" :min="min"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field v-model="end" type="date" label="Fin del evento"></v-text-field>
                                    </v-col>
                                    <v-col sm="6" md="2"></v-col>
                                    <v-col cols="12" sm="8" md="10">
                                        <v-color-picker v-model="color" dot-size="39" hide-mode-switch hide-sliders mode="hexa" show-swatches swatches-max-height="100"></v-color-picker>
                                    </v-col>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn type="submit" outlined @click.stop="dialog = false">Agregar</v-btn>
                                    </v-card-actions>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
                <v-card min-width="350px" flat>
                    <v-toolbar :color="selectedEvent.color" dark>
                        <v-btn icon @click="deleteEvent(selectedEvent)">
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form v-if="currentlyEditing !== selectedEvent.id">
                            {{selectedEvent.name}} - {{selectedEvent.details}}
                        </v-form>
                        <v-form v-else>
                            <v-text-field type="text" v-model="selectedEvent.name" label="Editar Nombre"></v-text-field>
                            <v-text-field v-model="selectedEvent.details" type="text" style="width: 100%" :min-height="100" label="Editar detalles"></v-text-field>
                            <v-text-field v-model="selectedEvent.start" type="date" label="Inicio del evento" :min="min"></v-text-field>
                            <v-text-field v-model="selectedEvent.end" type="date" label="Fin del evento"></v-text-field>
                            <p> Color</p>
                            <v-color-picker v-model="selectedEvent.color" dot-size="39" hide-canvas hide-mode-switch hide-sliders mode="hexa" show-swatches swatches-max-height="100"></v-color-picker>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn text color="secondary" @click="selectedOpen = false">
                            Cancel
                        </v-btn>
                        <v-btn text v-if="currentlyEditing !== selectedEvent.id" @click.prevent="editEvent(selectedEvent.id)">Editar</v-btn>
                        <v-btn text v-else @click.prevent="updateEvent(selectedEvent)">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
        </v-sheet>
        <template>
            <v-snackbar v-model="snackbar" :timeout="timeout" :color="clr">
                {{mensaje}}
                <template v-slot:action="{attrs}">
                    <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
                        Cerrar
                    </v-btn>
                </template>
            </v-snackbar>
        </template>
    </div>
</div>
</template>

<script>
import axios from 'axios'
export default {
    data: () => ({
        today: new Date().toISOString().slice(0, 10),
        focus: new Date().toISOString().slice(0, 10),
        type: 'month',
        typeToLabel: {
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            '4day': '4 Dias',
        },
        menu: false,
        menuV: false,
        timeout: 2500,
        mensaje: '',
        snackbar: false,
        clr: '',
        min: '',
        start: '',
        end: '',
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        events: [],
        name: '',
        details: '',
        color: '#1976D2',
        dialog: false,
        currentlyEditing: null,
        swatches: [
            ['#FF0000', '#AA0000', '#550000'],
            ['#FFFF00', '#AAAA00', '#555500'],
            ['#00FF00', '#00AA00', '#005500'],
            ['#00FFFF', '#00AAAA', '#005555'],
            ['#0000FF', '#0000AA', '#000055'],
        ],
    }),
    computed: {
        title() {
            const {
                start,
                end
            } = this
            if (!start || !end) {
                return ''
            }

            const startMonth = this.monthFormatter(start)
            const endMonth = this.monthFormatter(end)
            const suffixMonth = startMonth === endMonth ? '' : endMonth

            const startYear = start.year
            const endYear = end.year
            const suffixYear = startYear === endYear ? '' : endYear

            const startDay = start.day + this.nth(start.day)
            const endDay = end.day + this.nth(end.day)

            switch (this.type) {
                case 'month':
                    return `${startMonth} ${startYear}`
                case 'week':
                case '4day':
                    return `${startMonth} ${startDay} ${startYear} - ${suffixMonth} ${endDay} ${suffixYear}`
                case 'day':
                    return `${startMonth} ${startDay} ${startYear}`
            }
            return ''
        },
        monthFormatter() {
            return this.$refs.calendar.getFormatter({
                timeZone: 'GMT-5',
                month: 'long',
            })
        },
    },
    mounted() {
        this.$refs.calendar.checkChange();
    },
    created() {
        this.getEvents();
        this.min = new Date().toISOString();
    },
    methods: {
        async updateEvent(ev) {
            try {
                let formData = new FormData();
                formData.append("_method", "PUT");
                formData.append('id', ev.id)
                formData.append('name', ev.name)
                formData.append('details', ev.details)
                formData.append('start', ev.start)
                formData.append('end', ev.end)
                formData.append('color', ev.color)
                await axios.post('/api/putevents', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                    })
                    .then(response => {
                        if (response.status === 200) {
                            this.snackbar = true
                            this.mensaje = 'Modificación exitosa'
                            this.clr = '#4CAD50'
                        }
                    })
                    .catch((error) => {
                        if (error.response) {
                            this.snackbar = true
                            this.mensaje = 'Hubo un problema al modificar'
                            this.clr = '#FF5252'
                        }
                    })

                this.selectedOpen = false;
                this.currentlyEditing = null;

            } catch (error) {
                console.log(error);
            }
        },
        editEvent(id) {
            this.currentlyEditing = id
        },
        async deleteEvent(ev) {
            try {
                await axios.delete('/api/deleteevent?id=' + ev.id, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        if (response.status === 200) {
                            this.snackbar = true
                            this.mensaje = 'Eliminación exitosa'
                            this.clr = '#4CAD50'
                        }
                    })
                    .catch((error) => {
                        if (error.response) {
                            this.snackbar = true
                            this.mensaje = 'Hubo un problema al eliminar'
                            this.clr = '#FF5252'
                        }
                    })
                this.selectedOpen = false;
                this.getEvents();

            } catch (error) {
                console.log(error);
            }
        },
        async addEvent() {
            try {
                if (this.name && this.start && this.end) {
                    let formData = new FormData();
                    formData.append("name", this.name);
                    formData.append("details", this.details);
                    formData.append("start", this.start);
                    formData.append("end", this.end);
                    formData.append("color", this.color);
                    await axios.post('/api/postevents', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            },
                        })
                        .then((response) => {
                            if (response.status === 200) {
                                this.initialize()
                                this.clr = '#4CAF50'
                                this.mensaje = 'Evento agregado'
                                this.snackbar = true
                            }
                        })
                        .catch((error) => {
                            if (error.response) {
                                this.color = '#FF5252'
                                this.mensaje = 'Hubo un problema al agregar, intenta otra vez'
                                this.snackbar = true
                            }
                        })
                    this.getEvents();

                    this.name = null;
                    this.details = null;
                    this.start = null;
                    this.end = null;
                    this.clr = '#1976D2';

                } else {
                    console.log('Campos obligatorios');
                }
            } catch (error) {
                console.log(error);
            }
        },
        async getEvents() {
            try {
                const events = [];
                await axios.get('/api/getevents')
                    .then(response => (this.events = response.data))
                    .catch(function (error) {
                        console.log(error);
                    })
            } catch (error) {
                console.log(error);
            }
        },
        viewDay({
            date
        }) {
            this.focus = date
            this.type = 'day'
        },
        getEventColor(event) {
            return event.color
        },
        setToday() {
            this.focus = this.today
        },
        prev() {
            this.$refs.calendar.prev()
        },
        next() {
            this.$refs.calendar.next()
        },
        showEvent({
            nativeEvent,
            event
        }) {
            const open = () => {
                this.selectedEvent = event
                this.selectedElement = nativeEvent.target
                setTimeout(() => this.selectedOpen = true, 10)
            }

            if (this.selectedOpen) {
                this.selectedOpen = false
                setTimeout(open, 10)
            } else {
                open()
            }

            nativeEvent.stopPropagation()
        },
        updateRange({
            start,
            end
        }) {
            this.start = start
            this.end = end
        },
        nth(d) {
            return d > 3 && d < 21 ?
                'th' : ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][d % 10]
        },
    },
}
</script>