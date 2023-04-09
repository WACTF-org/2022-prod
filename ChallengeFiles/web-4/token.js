const crypto = require('crypto');
const TOKEN = crypto.randomBytes(32).toString("hex");
console.log(`Set token: ${TOKEN}`);
// const TOKEN = btoa(process.env.TOKEN);

module.exports = {
    TOKEN
}  