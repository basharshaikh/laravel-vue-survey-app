import {createStore} from 'vuex'
import axiosClient from '../axios'


const store = createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem('TOKEN')
        },
        currentSurvey: {
            loading: false,
            data: {}
        },
        surveys: {
            loading: false,
            links: [],
            data: []
        },
        questionTypes: ['text', 'select', 'radio', 'checkbox', 'textarea'],
        notification:{
            show: false,
            type: null,
            message: null
        },
        sidebar: {
            show: true
        },
        dashboard: {
            loading: false,
            data: {
                latestSurvey: {
                    image_url: null,
                    title:'',
                    created_at: '',
                    expire_date: '',
                    status: '',
                    questions: [],
                    answers: [],
                }
            }
        }
    },
    getters: {},
    actions: {
        geAllSurveyWithAns(){
            return axiosClient
            .get(`/survey-with-ans`)
            .then((res)=>{return res})
        },
        geSingleSurveyWithAns({}, id){
            return axiosClient
            .get(`/survey-answers/${id}`)
            .then((res)=>{return res})
        },
        getDashboardData({commit}){
            commit('dashboardLoading', true);
            return axiosClient.get('/dashboard')
            .then((res) => {
                commit('dashboardLoading', false);
                commit('setDashboardData', res.data);
                return res;
            }).catch((err) => {
                commit('dashboardLoading', false);
                return err;
            })
        },
        getSurveyBySlug({commit}, slug){
            commit('setCurrentSurveyLoading', true);
            return axiosClient
            .get(`/survey-by-slug/${slug}`)
            .then((res) => {
                commit('setCurrentSurvey', res.data);
                commit('setCurrentSurveyLoading', false);
                return res;
            })
            .catch((err) => {
                commit('setCurrentSurveyLoading', false);
                throw err;
            })
        },

        getSurvey({commit}, id){
            commit('setCurrentSurveyLoading', true);
            return axiosClient
            .get(`/survey/${id}`)
            .then((res)=>{
                commit('setCurrentSurvey', res.data);
                commit('setCurrentSurveyLoading', false);
                return res
            })
            .catch((err) => {
                commit('setCurrentSurveyLoading', false);
                throw err
            })
        },

        getSurveys({commit}, {url = null} = {}){
            url = url || '/survey'
            commit('setSurveysLoading', true);
            return axiosClient.get(url)
            .then((res) => {
                commit('setSurveysLoading', false);
                commit('setSurveys', res.data); // bellow is the mutation
                // setSurveys: (state, surveys) => {
                //     state.surveys.data = surveys.data;
                // },
                return res //{"data": [{"id": 24}]}
            })
        },

        saveSurvey({commit}, survey){
            /*
            |store.dispatch('saveSurvey', model.value) in serveyVue Compo 
            |console.log(survey); this 'survey is an object with form data
            */

            // delete survey.image_url;

            let response;
            if (survey.id) {
              response = axiosClient
                .put(`/survey/${survey.id}`, survey)
                .then((res) => {
                //   commit('setCurrentSurvey', res.data)
                  return res;
                });
            } else {
              response = axiosClient.post("/survey", survey).then((res) => {
                // commit('setCurrentSurvey', res.data)
                return res;
              });
            }
            
            return response;
        },

        deleteSurvey({}, id){
            return axiosClient.delete(`/survey/${id}`);
        },

        register({ commit }, user){
            // return fetch('http://localhost:8000/api/register', {
            //     headers: {
            //         "Content-Type": "application/json",
            //         Accept: "application/json",
            //     },
            //     method: "POST",
            //     body: JSON.stringify(user) // it will go though browser req body/payload as json
            // })
            // .then((res) => res.json())
            // .then((res) => {
            //     commit("setUser", res)
            // })
            return axiosClient.post('/register', user)
            .then(({data}) => {
                commit('setUser', data);
                return data;
            })
        },

        login({ commit }, user){
            // console.log(user) user is the {} and it is coming from Login.vue form user = {}
            // ^ { commit } for call only in function
            return axiosClient.post('/login', user)
            .then(({data}) => {
                commit('setUser', data);
                // console.log(data) 
                return data;
            })
        },

        logout({commit}){
            return axiosClient.post('/logout')
            .then(response => {
                commit('logout')
                return response
            })
        },
        saveSurveyAnswer({commit}, {surveyId, answers}) {
            return axiosClient.post(`/survey/${surveyId}/answer`, {answers});
        },
    },
    mutations: {
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data = userData.user;
            sessionStorage.setItem('TOKEN', userData.token);
        },
        sidebarToggle: (state, data) => {
            state.sidebar = data
        },
        dashboardLoading(state, loading){
            state.dashboard.loading = loading;
        },
        setDashboardData(state, data){
            state.dashboard.data = data;
        },
        setSurveysLoading: (state, loading) => {
            state.surveys.loading = loading;
        },

        setSurveys: (state, surveys) => {
            // debugger
            state.surveys.data = surveys.data;
            state.surveys.links = surveys.meta.links;
        },

        setCurrentSurveyLoading: (state, loading) => {
            state.currentSurvey.loading = loading;
        },

        setCurrentSurvey: (state, survey) => {
            state.currentSurvey.data = survey.data;
        },
        
        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
            sessionStorage.removeItem('TOKEN');
        },


        notify: (state, {message, type}) => {
            state.notification.show = true;
            state.notification.message = message;
            state.notification.type = type;

            setTimeout(() => {
                state.notification.show = false;
            }, 3000)
        }
    },
    modules: {}
})

export default store