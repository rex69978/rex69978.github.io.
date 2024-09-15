const uploadForm = document.getElementById('upload-form');
const fileInput = document.getElementById('file-input');
const uploadButton = document.getElementById('upload-button');
const uploadedFilesDiv = document.getElementById('uploaded-files');
const fileList = document.getElementById('file-list');


uploadButton.addEventListener('click', (e) => {
e.preventDefault();
const files = fileInput.files;
// Handle file upload logic here
// For example, you can use the Fetch API to upload files to a server
fetch('https://locahost:3000?upload', {
method: 'POST',
body: files
})
.then((response) => response.json())
.then((data) => {
console.log(data);
// Display uploaded files
const uploadedFiles = data.uploadedFiles;
uploadedFiles.forEach((file) => {
const fileElement = document.createElement('li');
fileElement.textContent = file.name;
fileList.appendChild(fileElement);
});
})
.catch((error) => console.error(error));
});
