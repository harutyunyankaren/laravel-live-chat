
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('chat-messages', require('./components/ChatMessages.vue'));
Vue.component('chat-form', require('./components/ChatForm.vue'));
var parts = window.location.href.split('/');
var roomId = parts.pop() || parts.pop();

const app = new Vue({
    el: '#app',

    data: {
        messages: [],
        roomId: roomId
    },

    created() {
        this.fetchMessages(roomId);
        Echo.channel('chat-room.' + roomId)
            .listen('MessageSent', (e) => {
            this.messages.push({
            message: e.message.message,
            sender_name: e.sender_name
        });
    });
    },

    methods: {
        fetchMessages(roomId) {
            axios.get('/messages/' + roomId).then(response => {
                this.messages = response.data;
        });
        },

        addMessage(message) {
            console.log('message');
            console.log(message);
            this.messages.push(message);

            axios.post('/messages/' + roomId, message).then(response => {
                console.log(response.data);
        });
        }
    }
});
