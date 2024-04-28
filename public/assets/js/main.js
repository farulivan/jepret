const baseUrl = ""; // Ensure this is correctly defined to include your API's base URL.

document.getElementById("uploadForm").onsubmit = async (e) => {
    e.preventDefault(); // Prevent the default form submission behavior.
    clearPage();

    const photoInput = document.getElementById("photo");
    if (photoInput.files.length === 0) {
        alert("No file selected.");
        return;
    }

    try {
        const photoFile = photoInput.files[0];
        const uploadUrl = await requestPhotoUrl(); // Fetch the pre-signed upload URL from your API.
        const uploadResponse = await uploadToS3(
            uploadUrl,
            photoFile,
            (progress) => {
                document.getElementById(
                    "uploadProgress"
                ).innerHTML = `Upload progress: ${Math.floor(progress * 100)}%`;
            }
        );

        if (uploadResponse.status === 200) {
            alert("Successfully uploaded file!");
            const imageURL = uploadUrl.split("?")[0];
            document.getElementById(
                "downloadURL"
            ).innerHTML = `Download URL: ${imageURL}`;
            document.getElementById("previewImage").src = imageURL;
        } else {
            console.error(uploadResponse.body);
            alert("Unable to upload file, check console for details");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Failed to get the upload URL or upload the photo.");
    }

    clearForm();
};

function requestPhotoUrl() {
    const url = baseUrl + "/photo-urls"; // Adjust if your endpoint differs.
    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + localStorage.getItem("access_token"),
        },
    };

    return fetch(url, options)
        .then((response) => {
            if (!response.ok)
                throw new Error("Failed to fetch the upload URL.");
            return response.json();
        })
        .then((data) => {
            if (data.ok) return data.data.photo_url;
            throw new Error("Invalid response data.");
        });
}

function uploadToS3(uploadURL, file, onProgress) {
    return new Promise((resolve) => {
        const xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", (e) => {
            if (e.lengthComputable) {
                onProgress(e.loaded / e.total);
            }
        });
        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                resolve({ status: xhr.status, body: xhr.responseText });
            }
        };
        xhr.open("PUT", uploadURL, true);
        xhr.setRequestHeader("x-amz-acl", "public-read");
        xhr.send(file);
    });
}

function clearForm() {
    document.getElementById("uploadForm").reset();
}

function clearPage() {
    document.getElementById("uploadProgress").innerHTML = "";
    document.getElementById("downloadURL").innerHTML = "";
    document.getElementById("previewImage").src = "#";
}
