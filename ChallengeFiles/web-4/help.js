const puppeteer = require("puppeteer");
const path = require("path");
const fs = require("fs");
const token = require("./token");

const CLEAN_MESSAGE_DELAY = 30 * 1000; // 30 seconds.
const CLEAN_UPLOADS_DELAY = 60 * 5 * 1000; // 5 minutes.

const delay = ms => new Promise(resolve => setTimeout(resolve, ms))

let messages = [];
let links = [];

function randChoice(choices) {
    return choices[Math.floor(Math.random()*choices.length)];
}

async function chat(req, res) {
    res.contentType("application/json");
    
    try {
        let userMessage = req.body.message.trim().toLowerCase();
        let userMessageRaw = req.body.message.trim();
        let userMessageTimestamp = req.body.timestamp;

        messages.push({
            message: req.body.message,
            timestamp: userMessageTimestamp
        });

        let greetings = [ "Hi", "Hello", "Hey", "Greetings" ];
        let punctuation = [ '.', ' ', '!' ];

        if (greetings.some(m => userMessage.toLowerCase().includes(m.toLowerCase()))) {
            await delay(3000);

            let randGreeting = randChoice(greetings);
            let randPunctuation = randChoice(punctuation);
            let randSupport = randChoice([ "support", "help", "helpcenter", "subscription" ]);
            let randChat = randChoice([ 'chat', '']);
            let randPunctuation2 = randChoice(punctuation);
            let randAssist = randChoice(["assist", "help today", "help", "support"]);
            let randName = randChoice(["Joey", "Case", "Meadow", "Sandra", "Atticus", "Karissa", "Leonel", "Olivia", "Francesca", "Bridger", "Randy", "Elliana", "Valeria", "Casey", "Joy", "Erika", "Jaslyn", "Jaqueline", "Lillianna", "Ernesto", "Chandler", "Jaida", "Matias", "Asia", "Robert", "Lizbeth", "Isla", "Rogelio", "Armando", "Shelby", "Lewis", "Quinn", "Amare", "Amy", "Clinton", "Stephanie", "Brooklynn", "Edward", "Peyton", "Katrina", "Natalia", "Penelope", "Regina", "Joshua", "Simone", "Malachi", "Danielle", "Armando", "Rayna", "Madalyn", "Hazel", "Sadie", "Landyn", "Tyrone", "Lexie", "Alberto", "Tucker", "Ellis", "Tommy", "Dominic", "Dayanara", "Fabian", "Brodie", "Carson", "Isabell", "Lyric", "Paige", "Jaron", "Ali", "Tristen", "Amelie", "Natalya", "Jesus", "Charlize", "Kendall", "Janiah", "Joe", "Jamal", "Kael", "Giancarlo", "Felix", "Trent", "Kayden", "Jovan", "Brianna", "Patrick", "Gage", "Monique", "Kamila", "Allison", "Lailah", "Charlie", "Liam", "Rose", "Sherlyn", "Darwin", "Helen", "Steven", "Damian", "Tamara"]);

            return res.send({ 
                "message": `${randGreeting}${randPunctuation} Welcome to the GZIP64 ${randSupport} ${randChat}${randPunctuation2} My name is ${randName}, how can I ${randAssist}?`
            });

        } else if (userMessage.startsWith("http://") || userMessage.startsWith("https://") || /^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}.*$/i.test(userMessage)) {
            await delay(1000);
            return res.send({ 
                "message": "It looks like you're trying to send us a link. Make sure it's only from our GZIP API site! We've been trained to not click malicious links! Try sending use the link from the first '/' onwards. e.g. '/uploads/12341234-1234-1234-1234-123412341234.png'"
            });

        } else if (/^\/uploads\/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}\.[A-Za-z0-9]{1,10}$/i.test(userMessage)) {            
            await delay(1000);

            let linksLen = links.length;
            if (linksLen > 3) {
                return res.send({ 
                    "message": `Woah there, you're going pretty quick with those links! We've got another ${linksLen} links for our support staff to check out, try again soon.`
                });
            }
            
            links.push(userMessageRaw);
            return res.send({ 
                "message": "We'll take a look at your link soon, thanks for trying out our trial!"
            });

        } else {
            await delay(1000);
            return res.send({
                "message": "We're glad you're interested in our API, please visit <a href=\"/trial\">/trial</a> to try it out. If you have any issues, feel free to send us the problematic URL and we'll get our engineers to check it out! 🙂" 
            });
        }
        
    } catch (e) {
        console.log(e);
        res.status(500);
        return res.send({ 
            "message": "[Internal Error]: Oops. Something went wrong with our chat."
        });
    }    
};

function getLatestMessages() {
    return messages;
}

async function cleanMessages() {
    setInterval(() => {
        console.log("Cleaning messages.");
        messages = [];
    }, CLEAN_MESSAGE_DELAY);
}

async function cleanUploads() {
    setInterval(() => {
        console.log("Cleaning uploads directory.");
        const uploadsDir = path.join(__dirname, "uploads");
        for (const file of fs.readdirSync(uploadsDir)) {
            fs.unlinkSync(path.join(uploadsDir, file));
        }
    }, CLEAN_UPLOADS_DELAY);
}

const HEADLESS_BROWSER = puppeteer.launch({
    headless: true,
    executablePath: "/usr/bin/chromium-browser", 
    args: [
        "--disable-gpu",
        "--disable-dev-shm-usage",
        "--disable-setuid-sandbox",
        "--no-sandbox",
    ]
});

(async function() {
    const TIMEOUT = 10 * 1000; // 10 seconds.
    await delay(3000);
    while (true) {
        await delay(TIMEOUT);

        console.log(`URL list length = ${links.length}`);

        if (links.length == 0)  {
            continue;
        }

        const link = links.pop();
        console.log(`Checking file: ${link}`);

        const browser = await HEADLESS_BROWSER;
        const page = await browser.newPage();
        try {
            await page.setCookie({
                name: "api_token",
                value: token.TOKEN,
                domain: "localhost",
                url: "http://localhost:8080/",
                path: "/",
                httpOnly: false,
                secure: false
            });
            
            await page.goto(`http://localhost:8080${link}`, {timeout: TIMEOUT});
            await delay(3000);   

        } catch (error) {
            //console.log(error);
        } finally {
            await page.close();
        }
    }
}());

module.exports = {
    chat,
    delay,
    getLatestMessages,
    cleanMessages,
    cleanUploads,
    messages
}