import { createStore } from "vuex";
import axiosClient from "../axios"


const store = createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem('TOKEN') as any
        },
        surveys: {
            loading: false,
            data: {},
            links: []
        },
        dashboard: {
            loading: false,
            data: {}
        },
        questionTypes: ['text', 'radio', 'textarea', 'select', 'checkbox'],
        Notification: {
            message: null,
            type: null,
            show: false
        },
        currentSurvey: {
            loading: false,
            data: {}
        }
    },
    getters: {},
    actions: {
        getDashboardData({ commit }) {
            commit('dashboardLoading', true)
            return axiosClient.get('/dashboard')
                .then((res) => {
                    commit('dashboardLoading', false)
                    commit('setDashboardLoading', res.data)
                }).catch(error => {
                    commit('dashboardLoading', false)
                    return error
                })
        },
        getSurvey({ commit }, id) {
            commit("setCurrentSurveyLoding", true)
            return axiosClient.get(`/survey/${id}`).then((res) => {
                commit("setCurrentSurvey", res.data)
                commit("setCurrentSurveyLoding", false)
                return res
            })
                .catch((err) => {
                    commit("setCurrentSurveyLoding", true)
                    throw err
                })
        },
        saveSurvey({ commit }, survey) {
            delete survey.image_url;
            let response;
            if (survey.id) {
                response = axiosClient.put(`/survey/${survey.id}`, survey).then((res) => {
                    commit("setCurrentSurvey", res.data);
                    return res
                })
            } else {
                response = axiosClient.post('/survey', survey).then((res) => {
                    commit("setCurrentSurvey", res.data);
                    return res
                })
            }
            return response;
        },
        getSurveys({ commit }, { url = null } = {}) {
            url = url || '/survey'
            commit('setSurveysloading', true)
            return axiosClient.get(url).then((res) => {
                commit('setSurveysloading', false)
                commit('setSurveys', res.data)
                return res
            })
        },
        saveSurveyAnswer({ commit }, { surveyId, answers }) {
            return axiosClient.post(`/survey/${surveyId}/answer`, { answers })
        },
        getSurveyBySlug({ commit }, slug) {
            commit('setCurrentSurveyLoding', true)
            return axiosClient.get(`/survey-by-slug/${slug}`).then((res) => {
                commit('setCurrentSurvey', res.data)
                commit('setCurrentSurveyLoding', false);
                return res;
            })
                .catch((err) => {
                    commit('setCurrentSurveyLoding', false);
                    throw err;
                })
        },
        deleteSurvey({ }, id) {
            return axiosClient.delete(`/survey/${id}`)
        },
        register({ commit }, user) {
            return axiosClient.post('/register', user)
                .then(({ data }) => {
                    commit('setUser', data)
                    return data
                })
        }, login({ commit }, user) {
            return axiosClient.post('/login', user)
                .then(({ data }) => {
                    commit('setUser', data)
                    return data
                })
        },
        logout({ commit }) {
            return axiosClient.post('/logout')
                .then(response => {
                    commit('logout')
                    return response
                })
        }
    },
    mutations: {
        dashboardLoading: (state, loading) => {
            state.dashboard.loading = loading
        },
        setDashboardLoading: (state, data) => {
            state.dashboard.data = data
        },
        setCurrentSurveyLoding: (state, loading) => {
            state.currentSurvey.loading = loading
        },
        setSurveysloading: (state, loading) => {
            state.surveys.loading = loading
        },
        setCurrentSurvey: (state, survey) => {
            state.currentSurvey.data = survey.data
        },
        setSurveys: (state, survey) => {
            state.surveys.data = survey.data
            state.surveys.links = survey.meta.links
        },
        logout: state => {
            state.user.token = null;
            state.user.data = {} as any;
            sessionStorage.removeItem('TOKEN')
        },
        setUser: (state, user) => {
            state.user.token = user.token
            state.user.data = user.user
            sessionStorage.setItem("TOKEN", user.token)
        },

        notify: (state, { message, type }) => {
            state.Notification.message = message
            state.Notification.type = type
            state.Notification.show = true
            setTimeout(() => {
                state.Notification.show = false
            }, 3000);
        }

    },
    modules: {}
})

export default store;
