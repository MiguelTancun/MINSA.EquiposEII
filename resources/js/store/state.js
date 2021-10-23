const userDefaults = {
    uid         : 0,          // From Auth
    displayName : "John Doe", // From Auth
    about       : "Dessert chocolate cake lemon drops jujubes. Biscuit cupcake ice cream bear claw brownie brownie marshmallow.",
    status      : "online",
    userRole    : "admin",
  }

  const state = {
    AppActiveUser           : userDefaults,
    // Auth persistedSate
    token: null,
    isAuthenticated: false
}

export default state
