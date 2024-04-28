import { useEffect, useState, useRef } from "react";
import axios from "axios";
import axiosClient from "../axios-client";
import { useNavigate } from "react-router-dom";
import { useStateContext } from "./context/ContextProvider";

function MainPage() {
    const [posts, setPosts] = useState([]);
    const [selectedImage, setSelectedImage] = useState(null);
    const [imagePreviewUrl, setImagePreviewUrl] = useState("");
    const [caption, setCaption] = useState("");
    const [uploadProgress, setUploadProgress] = useState(0);
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
        const file = event.target.files[0];
        console.log(file);
        if (file) {
            setSelectedImage(file);
            uploadImage(file);
        }
    };

    const uploadImage = async (file) => {
        // Fetch upload URL from your backend
        try {
            const response = await axiosClient.post("/photo-urls");
            const uploadURL = response.data;
            await uploadToS3(uploadURL, file);
        } catch (error) {
            console.error("Error fetching upload URL", error);
            alert("Could not fetch upload URL.");
        }
    };

    const uploadToS3 = async (uploadURL, file) => {
        const options = {
            onUploadProgress: (progressEvent) => {
                const percentCompleted = Math.round(
                    (progressEvent.loaded * 100) / progressEvent.total
                );
                setUploadProgress(percentCompleted);
            },
            headers: {
                "Content-Type": file.type,
                "x-amz-acl": "public-read",
            },
        };
        try {
            await axios.put(uploadURL, file, options);
            alert("File uploaded successfully");
            setSelectedImage(null); // Reset after upload
        } catch (error) {
            console.error("Upload failed", error);
            alert("Upload failed, please try again.");
        }
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        if (!selectedImage) {
            alert("Please select an image first.");
            return;
        }

        // Assume '/photo-urls' endpoint is used to get a presigned URL for uploads
        const uploadConfig = await axios.get("/photo-urls");
        const { url } = uploadConfig.data;

        // Upload the image to the presigned URL
        const formData = new FormData();
        formData.append("file", selectedImage);

        await axios.put(url, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        // After successful upload, submit the post details
        const submitResponse = await axios.post("/posts", {
            photo_url: url.split("?")[0], // Assuming URL needs to be cleaned from parameters
            caption,
        });

        if (submitResponse.status === 200) {
            alert("Post created successfully!");
            // Reset state and close modal logic here
        } else {
            alert("Failed to create post.");
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
