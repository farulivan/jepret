import { useEffect, useState, useRef } from "react";
import axiosClient from "../axios-client";
import { useNavigate } from "react-router-dom";
import { useStateContext } from "./context/ContextProvider";

function MainPage() {
    const [posts, setPosts] = useState([]);
    const [selectedImage, setSelectedImage] = useState(null);
    const [imagePreviewUrl, setImagePreviewUrl] = useState("");
    const [caption, setCaption] = useState("");
    const [, setUploadProgress] = useState(0);
    const fileInputRef = useRef(null);
    const { logout } = useStateContext();
    const navigate = useNavigate();

    useEffect(() => {
        fetchPosts();
    }, []);

    const fetchPosts = async () => {
        try {
            const response = await axiosClient.get("/posts");
            setPosts(response.data.data.posts);
        } catch (error) {
            console.error("Failed to fetch posts", error);
            alert("Failed to load posts. Please try again later.");
        }
    };

    const handleLogout = () => {
        logout();
        navigate("/login");
    };

    const handleImageSelection = (event) => {
        if (event.target.files && event.target.files[0]) {
            setImagePreviewUrl(URL.createObjectURL(event.target.files[0]));
            setSelectedImage(event.target.files[0]);
        }
    };

    function uploadToS3(uploadURL, file, onProgress) {
        return new Promise((resolve) => {
            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener("progress", (e) => {
                if (e.lengthComputable) {
                    onProgress(e.loaded / e.total);
                }
            });
            xhr.onreadystatechange = () => {
                if (xhr.readyState !== xhr.DONE) {
                    return;
                }
                resolve({ status: xhr.status, body: xhr.responseText });
            };
            xhr.open("PUT", uploadURL, true);
            xhr.setRequestHeader("x-amz-acl", "public-read");
            xhr.send(file);
        });
    }

    const handleSubmit = async (event) => {
        event.preventDefault();
        if (!selectedImage) {
            alert("Please select an image first.");
            return;
        }

        try {
            // Get the presigned URL for the upload
            const uploadConfig = await axiosClient.post("/photo-urls");
            const { photo_url } = uploadConfig.data.data;
            const uploadResponse = await uploadToS3(
                photo_url,
                selectedImage,
                setUploadProgress
            );
            // After successful upload, submit the post details
            if (uploadResponse.status === 200) {
                const submitResponse = await axiosClient.post("/posts", {
                    photo_url: photo_url.split("?")[0], // Assuming URL needs to be cleaned from parameters
                    caption,
                });

                if (submitResponse.status !== 200) {
                    alert("Failed to create post.");
                }

                alert("Post created successfully!");
                setSelectedImage(null);
                setImagePreviewUrl("");
                setCaption("");

                // refresh the page so the new post will be shown in the main page. I know this might be not
                // the best solution to re-render the whole page in React, but I'm not a React developer so
                // this is the best method I could think of. 😂
                window.location.reload();
            }
        } catch (error) {
            console.error("Error during form submission", error);
            alert("Error during form submission.");
        }
    };

    const handleImageChange = (event) => {
        if (event.target.files && event.target.files[0]) {
            const file = event.target.files[0];
            setSelectedImage(file);
            const newImagePreviewUrl = URL.createObjectURL(file);
            setImagePreviewUrl(newImagePreviewUrl);
            // Note: Revoking the URL should happen after it's no longer needed, typically after loading
        }
    };

    return (
        <div>
            <nav className="container-fluid">
                <ul>
                    <li>
                        <strong>Jepret</strong>
                    </li>
                </ul>
                <ul>
                    <li>
                        <div className="action-button">
                            <button
                                onClick={() => fileInputRef.current.click()}
                            >
                                <img
                                    src="assets/icons/camera.svg"
                                    alt="Select Image"
                                />
                                <input
                                    type="file"
                                    ref={fileInputRef}
                                    style={{ display: "none" }}
                                    accept="image/*"
                                    onChange={handleImageSelection}
                                />
                            </button>
                        </div>
                    </li>
                    <li>
                        <div className="action-button" id="btnLogout">
                            <button onClick={handleLogout}>
                                <img
                                    src="assets/icons/log-out.svg"
                                    alt="Log Out"
                                />
                            </button>
                        </div>
                    </li>
                </ul>
            </nav>
            <main className="container" id="postListContainer">
                <div className="row">
                    {posts.map((post, index) => (
                        <div key={index} className="col-md-4">
                            <article className="post-container">
                                <figure>
                                    <img
                                        src={post.photo_url}
                                        alt={post.caption}
                                    />
                                </figure>
                                <div className="caption-container">
                                    <p className="caption">{post.caption}</p>
                                    <p className="meta">
                                        @{post.author_handle} -{" "}
                                        {new Date(
                                            post.created_at * 1000
                                        ).toLocaleString()}
                                    </p>
                                </div>
                            </article>
                        </div>
                    ))}
                </div>
            </main>
            {selectedImage && (
                <dialog open id="modalCreatePost">
                    <article>
                        <header>
                            <a
                                href="#close"
                                aria-label="Close"
                                className="close"
                                id="btnCancelCreatePost"
                                onClick={() => setSelectedImage(null)}
                            ></a>
                            <p>Create Post</p>
                        </header>
                        <figure>
                            <img
                                src={imagePreviewUrl}
                                alt="Selected"
                                id="selectedImage"
                                onLoad={() =>
                                    URL.revokeObjectURL(imagePreviewUrl)
                                }
                            />
                        </figure>
                        <form
                            id="formCreatePost"
                            onSubmit={handleSubmit}
                            autoComplete="off"
                        >
                            <input
                                type="text"
                                name="imageCaption"
                                id="imageCaption"
                                placeholder="Insert caption here..."
                                value={caption}
                                onChange={(e) => setCaption(e.target.value)}
                                required
                            />
                            <input
                                type="file"
                                name="imageFileInput"
                                id="imageFileInput"
                                accept="image/*"
                                ref={fileInputRef}
                                onChange={handleImageChange}
                                style={{ display: "none" }} // Hide the input but make it functional
                            />
                            <button type="submit" className="contrast">
                                Publish
                            </button>
                        </form>
                    </article>
                </dialog>
            )}
        </div>
    );
}

export default MainPage;
