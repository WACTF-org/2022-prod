'use strict';

// Import the helpful libraries.
const token = require("./token");
const upload = require("./upload");
const help = require("./help");
const fs = require("fs");
const path = require("path");
const express = require("express");
const { exec } = require("child_process");
const serveIndex = require("serve-index");

// Setup secure regex.
const allowCharRegex = /[^a-zA-Z0-9\ ]/g;
const hasNonPrintable = (s) => allowCharRegex.test(s);
const escapeHTML = s => s.replace(/[&<>'"]/g,
  tag => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    "'": "&#39;",
    "\"": "&quot;"
  }[tag]));


// Setup server details.
const PORT = 8080;
const HOST = "0.0.0.0";
const app = express();

app.use(express.json())
app.set("view engine", "ejs");

// STRONG CSP.
app.use(function(req, res, next) {
  res.setHeader("Content-Security-Policy", "default-src 'self'; img-src http: https:; style-src 'unsafe-inline' http: https:; object-src 'none';base-uri 'none'; font-src http: https:; frame-src 'self'; connect-src *; script-src 'sha256-KSf6+82tkxvKPR/0916wmEhOt91Lw8UFcTkhZ86qwGo=' 'sha256-8TMrxW/W730TxIFfbDxM4Qmk6H9l+0+/k6VmZiyOqwo=' 'sha256-uiO//DbvswiStsyiG3bbtDcoUqQIGKvRzR6fffIbvs0=' 'sha256-DOneOY3n5+xj6Da3AJBkPHxaPynqSlGaZ9790gbBOsE=' 'sha256-6di7rhdey/6pZfMNSqLZ2pcQ54475Zwc879diBXvpW4=' 'sha256-+8RZJua0aEWg+QVVKg4LEzEEm/8RFez5Tb4JBNiV5xA=' 'sha256-s4TPC67sA6qeFEP9ZwzUHJ7JhN75p7Tb/XrS8ZfVtXM=' 'sha256-cb92bJJyZ+FBAZoDvArULVHBlkjEE52hh0HzXianXQg=' 'sha256-wlnOm07jUmQpocWl3PtIevHHdkXAhimBiF4XDoFth6c=' 'sha256-/suvoh6ddffxnnzI4noXPoRddQWBvLdY4QIsp706s/c=' 'sha256-XtTTESI34wWuEbPJce+gXdp/zHCMjgtvLflKDDJsNzU=' 'sha256-ExwNgpZ/7QXhkg5Rng6m7JGrl7fEBID3L4r4aAu6Hwo=' 'sha256-jQSAUrfn72DS8Mj47Mg2K8X21PJDwDC2ogv1H4UH4mU=' 'sha256-o5AUfgOkNFWuInA78GVCoVdGoN9eTaB3Hxu+ep7TTrY=' 'sha256-b0ooWP2rUwglFwyaeJ4KdHl/LPCNwWjcS8qYeuZudWQ=' 'sha256-x2nEB1+s2CcDkTgveORhEd7FlGBVs1PUacYQfQqHMtE=' 'sha256-1tkilYn13IJFtygH9l2i57iw3A4aEzDmRUoN2NHxBlM=' 'sha256-40i5gvF3JzhaTP1uq4muILomrs/uEwXj+fS60JnshYM=' 'sha256-+3xgqfaj59RidkNCsaBCFrqFtEkU8Q57AoVtOOTYMBs=' 'sha256-LhDj81GMy8p4W8wPUeC0Zx4WnbVV7XNFiBSRTyk18Ys=' 'sha256-OmYCVIx1jMpOJ2LVDoAqVAC0xmO2pjjXapFjWYRn3tQ=' 'sha256-nwtTA1EH0LF+Dgca7hMOrfzGQJx5yHOWv+YKiDXTD5k=' 'sha256-afY2tUSTlf/wpYG2nP0w5eMvbbNUANiQQGsm8dhc/Cc=' 'sha256-hQoMVJoPumNozrz9N4yjtNKqy5Gpb5ftloLXAQIEhtk=';");
  return next();
});

// Static files.
app.use("/static", express.static(path.join(__dirname, "public")))
app.use("/public/cdn", express.static(path.join(__dirname, "/public/cdn")), serveIndex(path.join(__dirname, "/public/cdn")));

// Where the magic happens.
function doCompressionExec(precmd, query, res) {
  exec(`(${precmd} '${query}' | gzip | base64 -w0)`, (error, stdout, stderr) => {
    if (error) {
      res.status(500);
      res.send({
        "error": `${error.message}`
      });
    }
    else if (stderr) {
      res.status(500);
      res.send({
        "error": `${stderr}`
      });

    } else {
      res.status(200);
      res.send({
        "data": `${stdout}`
      });

    }
  });
}

// Figure out what to compress.
function doCompression(query, file, apiToken, res) {
  try {
    // Only members with the API token can compress arbitrary data!
    if (!!apiToken && (typeof apiToken === "string" || apiToken instanceof String)) {
      apiToken = apiToken.trim();

      if (apiToken == token.TOKEN) {
        if (query === null || hasNonPrintable(query) || Array.isArray(query)) {
          res.status(500);
          return res.send({
            "error": "Failed to run query."
          });
        }

        return doCompressionExec("printf", query, res);

      // Whoops! Invalid token!
      } else { 
        res.status(403);
        return  res.send({
          "error": "Token is invalid."
        });
      }

    // Guests can upload images to test out the API.
    } else if (!!file && (typeof file === "string" || file instanceof String)) {
      if (/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}\.[A-Za-z0-9]{1,10}$/i.test(file) == false) {
        res.status(500);
        return res.send({
          "error": "Failed to run query."
        });
      }

      const uploadDirectory = path.join(__dirname, "uploads");
      const filePath = path.join(uploadDirectory, file);
      return doCompressionExec("cat", filePath, res);
    
    }

    // Invalid API params.
    res.status(403);
    return res.send({
      "error": "Oops, invalid API parameters."
    });

    // Something unexpected happened.
  } catch (e) {
    console.log(e)
    res.status(403);
    return res.send({
      "error": "Oops, something went wrong. Did you supply your API token?"
    });
  }
}

// API compress route.
app.route("/api/v1/compress")
  .get((req, res) => {
    doCompression(req.query.query, req.query.file, req.query.api_token, res);
  })
  .post((req, res) => {
    doCompression(req.body.query, req.query.file, req.body.api_token, res);
  });

// Chat to our super friendly and helpful team!
app.post("/chat", function (req, res) {
  help.chat(req, res);
});

// Eveything JavaScript, all the time.
app.get("/app.js", function (req, res) {
  res.contentType("application/javascript");
  res.send(fs.readFileSync("./public/app.js", "utf8"));
});

// Provide amazing styling to our clients.
app.get("/app.css", function (req, res) {
  res.contentType("text/css");
  res.send(fs.readFileSync("./public/app.css", "utf8"));
});

// Upload endpoint for guests!
app.post("/upload", async (req, res, next) => {
  upload.handleUpload(req, res, next);
});

// View public uploads from guests!
app.get("/uploads", function (req, res) {
  try {
    res.render("pages/uploads", {
      uploads: fs.readdirSync("./uploads/")
    });
  } catch (e) {
    console.error(e);
  }
});

// Get the public guest uploads!
app.get("/uploads/:file", function (req, res) {
  upload.getFile(req, res);
});

// Only for hack3rs!
app.get("/code", function (req, res) {
  try {
    let data = fs.readFileSync("server.js", "utf8");
    res.send(`<html><body><pre>${escapeHTML(data.toString())}</pre></body></html>`);
  } catch (e) {
    res.send("Something went wrong, contact the CTF developer.");
  }
});

// Trial endpoint.
app.get("/trial", function(req, res) {
  res.render("pages/fileupload");
});

// / sweet /.
app.get("/", (req, res) => {
  let data = fs.readFileSync("./public/index.html", "utf8");
  res.send(data.toString());
});

app.listen(PORT, HOST);
console.log(`Running on http://${HOST}:${PORT}`);

help.cleanMessages();
help.cleanUploads();