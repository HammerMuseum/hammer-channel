module.exports = {
  "extends": ["airbnb-base"],
  "globals": {
    "Vue": true,
    "window": true,
  },
  ignorePatterns: ["webpack.config.*"],
  "rules": {
    "quotes": ["error", "single", { "avoidEscape": true, "allowTemplateLiterals": true }],
    "func-names": ["off"],
    "prefer-arrow-callback": ["off"],
    "prefer-destructuring": ["off"],
    "no-debugger": "warn",
  }
}
