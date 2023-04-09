const express = require("express");
const bodyParser = require("body-parser");
const app = express();
const puppeteer = require("puppeteer");
const PORT = 80;
const delay = ms => new Promise(resolve => setTimeout(resolve, ms));
const CLEAN_MESSAGE_DELAY = 120 * 1000; // 120 seconds.
const CHECK_MESSAGE_DELAY = 15 * 1000; // 15 seconds.
const FLAG = "WACTF{100g_suG@r_1_Egg_155G_fL0uR_1_sCriPT_taG}";

app.use(bodyParser.urlencoded({ extended: true }));
app.set("view engine", "ejs");
app.use(express.static("public"));

let posts = [];
app.post("/addpost", function (req, res) {
  let newPost = req.body.newpost;

  if (newPost.trim().length == 0) {
    res.redirect("/?error=Post cannot be empty.");
  } else {
    posts.push({
      data: newPost,
      time: (new Date()).toLocaleString()
    });

    res.redirect("/");
  }
});

app.post("/clean", function (req, res) {
  posts = [];
  res.redirect("/");
});

app.get("/", function (req, res) {
  res.render("index", { post: posts });
});

app.listen(PORT, function () {
  console.log(`Running on port ${PORT}`);
});

async function cleanPosts() {
  while (true) {
    console.log("[*] Cleaning posts.");
    posts = [];
    await delay(CLEAN_MESSAGE_DELAY);
  }
}

async function readMessages() {
  const browser = await puppeteer.launch({
    headless: true,
    executablePath: "/usr/bin/chromium-browser",
    ignoreHTTPSErrors: true,
    dumpio: true,
    args: [
      "--disable-gpu",
      "--disable-dev-shm-usage",
      "--disable-setuid-sandbox",
      "--no-sandbox",
      "--proxy-server='direct://'",
      "--proxy-bypass-list=*"
    ]
  });
  const page = await browser.newPage();

  await delay(3000);
  await page.setCookie({
    name: "FLAG",
    value: FLAG,
    url: `http://127.0.0.1/`,
    httpOnly: false,
    secure: false
  });
  await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36')

  while (true) {
    console.log(`[*] Checking posts.`);
    try {

      const resp = await page.goto(`http://127.0.0.1/`, { timeout: 0 });

      console.log(`[*] Response received:`);
      console.log(await resp.headers())
      console.log(await resp.text())

      await delay(3000);

    } catch (error) {
      console.log(error);
    } finally {
      await delay(CHECK_MESSAGE_DELAY);
    }
  }
}

cleanPosts();
readMessages();