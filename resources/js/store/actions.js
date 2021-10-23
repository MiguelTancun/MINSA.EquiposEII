const actions = {
      // /////////////////////////////////////////////
    // User/Account
    // /////////////////////////////////////////////

    updateUserInfo({ commit }, payload) {
        commit('UPDATE_USER_INFO', payload)
      },
  
      setToken({commit}, token) {
        commit("setToken", token);
      },
      logout({commit}) {
        let token = null;
        localStorage.clear();
        commit("logout", token);
  
      },
      setUserInfo({commit}, userInfo) {
        commit("setUserInfo", userInfo);
      }
}

export default actions