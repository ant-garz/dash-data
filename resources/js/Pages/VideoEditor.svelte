<script>
    import Layout from "./+layout.svelte";
    import { onMount } from "svelte";
    import {
        Container,
        Row,
        Col,
        Card,
        CardBody,
        Table,
        Spinner,
    } from "@sveltestrap/sveltestrap";
    import api from "../api";

    import { theme } from "../stores/theme.js";

    $: currentTheme = $theme;

    export let id; // video id from route param

    let video = null;
    let error = "";
    let success = "";

    let selectedFiles = [];
    let uploading = false;
    let uploadProgress = 0;

    onMount(async () => {
        error = "";
        try {
            const res = await api.get(`/api/videos/${id}`);
            video = res.data;
        } catch (err) {
            error = "Failed to load video information.";
        }
    });

    function handleFileChange(event) {
        selectedFiles = Array.from(event.target.files);
    }

    async function handleUpload(e) {
        e.preventDefault();
        error = "";
        success = "";

        if (selectedFiles.length === 0) {
            error = "Please select one or more segment files to upload.";
            return;
        }

        const formData = new FormData();
        // Include video title if needed (optional)
        formData.append("title", video.title);

        selectedFiles.forEach((file) => {
            formData.append("segments[]", file);
        });

        uploading = true;
        uploadProgress = 0;

        try {
            await api.post(`/api/videos/${id}/segments`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
                onUploadProgress: (progressEvent) => {
                    if (progressEvent.lengthComputable) {
                        uploadProgress = Math.round(
                            (progressEvent.loaded * 100) / progressEvent.total,
                        );
                    }
                },
            });
            success = "Segments uploaded successfully!";
            selectedFiles = [];
        } catch (err) {
            error = "Upload failed. Please try again.";
        } finally {
            uploading = false;
            uploadProgress = 0;
        }
    }

    async function handleCancel() {
        if (!confirm("Cancel upload and delete this video?")) return;

        try {
            await api.delete(`/api/videos/${id}/cancel`);
            success = "Upload canceled and video deleted.";
            // Optionally redirect user, e.g. window.location.href = "/videos";
        } catch {
            error = "Failed to cancel upload.";
        }
    }
</script>

<Layout>
    <Container class="py-5">
        {#if loading}
            <Spinner color="primary" />
        {:else if error}
            <p class="text-danger">{error}</p>
        {:else}
            <Row class="justify-content-center">
                <Col md="10" lg="8">
                    <Card theme={currentTheme === "dark" ? "dark" : "light"}>
                        <CardBody>
                            <h3 class="mb-3">{video.title}</h3>

                            <!-- Main stitched video -->
                            <video
                                src={`/storage/${video.filename}`}
                                controls
                                class="w-100 mb-4"
                            ></video>

                            <ul class="list-unstyled mb-4">
                                <li>
                                    <strong>Duration:</strong>
                                    {video.duration_readable}
                                </li>
                                <li><strong>Format:</strong> {video.format}</li>
                                <li><strong>Codec:</strong> {video.codec}</li>
                                <li>
                                    <strong>Size:</strong>
                                    {(video.size / 1048576).toFixed(1)} MB
                                </li>
                                <li>
                                    <strong>Resolution:</strong>
                                    {video.width}×{video.height}
                                </li>
                                <li>
                                    <strong>Recorded At:</strong>
                                    {video.recorded_at}
                                </li>
                            </ul>

                            {#if video.segments?.length}
                                <h5 class="mt-4">📂 Segments</h5>
                                <Table striped responsive class="mt-2">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Filename</th>
                                            <th>Duration</th>
                                            <th>Start</th>
                                            <th>End</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {#each video.segments as seg, i}
                                            <tr>
                                                <td>{i + 1}</td>
                                                <td>{seg.filename}</td>
                                                <td>{seg.duration ?? "—"}s</td>
                                                <td>{seg.start_time ?? "—"}</td>
                                                <td>{seg.end_time ?? "—"}</td>
                                            </tr>
                                        {/each}
                                    </tbody>
                                </Table>
                            {:else}
                                <p class="text-muted">
                                    No segments found for this video.
                                </p>
                            {/if}
                        </CardBody>
                    </Card>
                </Col>
            </Row>
        {/if}
    </Container>
</Layout>
