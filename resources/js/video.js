import api from "./api";

export async function createVideo(title, recordedAt = null) {
    const payload = { title };

    const response = await api.post("/videos", payload);
    return response.data; // returns created video object (with id)
}

export async function uploadSegments(videoId, segmentFiles) {
    const formData = new FormData();

    segmentFiles.forEach((file) => formData.append("segments[]", file));

    const response = await api.post(`/videos/${videoId}/segments`, formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });

    return response.data; // updated video with segments
}

export async function stitchVideo(videoId) {
    const response = await api.post(`/videos/${videoId}/stitch`);
    return response.data; // status message or stitched video info
}

export async function updateVideoMetadata(videoId, metadata) {
    // metadata is an object with any of title, recorded_at, etc
    const response = await api.patch(`/videos/${videoId}`, metadata);
    return response.data; // success message
}

export async function cancelUpload(videoId) {
    // if the user doesn't finish their upload, remove all data linked in thus far
    api.delete(`/videos/${videoId}/cancel`)
        .then(() => {
            console.log("Upload canceled and cleaned up");
            // redirect or update UI accordingly
        })
        .catch((error) => {
            console.error("Error canceling upload:", error);
        });
}
