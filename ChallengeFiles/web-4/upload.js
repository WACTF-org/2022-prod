const fs = require("fs");
const path = require("path");
const formidable = require("formidable");
const { randomUUID } = require("crypto");

async function handleUpload(req, res, next) {
  const uploadDirectory = path.join(__dirname, "uploads");
  const uploadForm = new formidable.IncomingForm({
    maxFileSize: 1024 * 500, // 500kb max
    uploadDir: uploadDirectory
  });
  
  uploadForm.parse(req, function (err, fields, files) {
    if (err) {
      console.log("Error parsing the file.");
      res.status(500);
      return res.send({
        status: "Fail",
        message: "Whoops, we can't accept that."
      });
    }
    
    // Check filename is valid - we need the extension.
    const originalFilename = files.files.originalFilename;

    if (!originalFilename.includes(".")) {
      console.log("Error parsing file name.");
      res.status(500);
      return res.send({
        status: "Fail",
        message: "Whoops, we can't accept that."
      });
    }
    
    // Check the file mimetype and construct the new filename with a random uuid.
    const extension = originalFilename.split(".").pop().trim();
    const newFileName = `${randomUUID()}.${extension}`;    
    const mimeType = files.files.mimetype.trim();

    if (/image\/(jpe?g|png|gif)$/i.test(mimeType) === false) {
      res.status(500);
      return res.send({
        status: "Fail",
        message: "Whoops, we can't accept that."
      });
    }

    // Upload the file.
    try {
      const fileServerPath = path.join(uploadDirectory, newFileName);
      
      console.log(`Uploading new file to: ${fileServerPath}`);
      fs.renameSync(files.files.filepath, fileServerPath, function (err) {
        if (err) throw err;
      });

    } catch(error){
      console.log(`Failed to upload: ${error}`);
      res.status(500);
      return res.send({
        status: "Fail",
        message: "Whoops, we can't accept that."
      });
    }

    // Complete.
    res.status(200);
    return res.render("pages/uploaded", {
      uploadPath: `/uploads/${newFileName}`,
      gzipPath: `/api/v1/compress/?file=${newFileName}`
    });
  });
}

async function getFile(req, res) {
  try {
    const fileChoice = req.params.file;
    const splitFile = fileChoice.split(".");
    const extension = splitFile.pop().trim();

    if (["jpeg", "jpg", "svg", "png", "bmp"].includes(extension)) {
      res.setHeader("Content-Disposition", `attachment; filename=${fileChoice}`);
    }

    res.send(fs.readFileSync(`./uploads/${fileChoice.replace(/[^a-zA-Z0-9-\.]/gi,"")}`).toString());
  } catch (e) { }
}

module.exports = {
    handleUpload,
    getFile
}  