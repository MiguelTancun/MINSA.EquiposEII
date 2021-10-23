const mutations = {

  // /////////////////////////////////////////////
  // User/Account
  // /////////////////////////////////////////////

  // Updates user info in state and localstorage
  UPDATE_USER_INFO(state, payload) {

    // Get Data localStorage
    let userInfo = JSON.parse(localStorage.getItem("userInfo")) || state.AppActiveUser

    for (const property of Object.keys(payload)) {

      if (payload[property] != null) {
        // If some of user property is null - user default property defined in state.AppActiveUser
        state.AppActiveUser[property] = payload[property]

        // Update key in localStorage
        userInfo[property] = payload[property]
      }


    }
    // Store data in localStorage
    localStorage.setItem("userInfo", JSON.stringify(userInfo))
  },

  setToken(state, token) {
    state.token = token;
    if (token && state.token != null) {
      state.isAuthenticated = true;
    } else {
      state.isAuthenticated = false;
    }
  },
  logout(state) {
    state.token = null;
    state.isAuthenticated = false;
    state.AppActiveUser = null;
  },
  setUserInfo(state, userInfo) {
    state.AppActiveUser = userInfo;
  }

}

export default mutations