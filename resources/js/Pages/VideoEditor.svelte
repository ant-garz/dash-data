<script>
    import Layout from "./+layout.svelte";
    import { onMount } from "svelte";
    import {
        Container,
        Row,
        Col,
        Card,
        CardBody,
        Spinner,
        FormGroup,
        Label,
        Input,
        Button,
        Progress,
        Alert,
        Table
    } from "@sveltestrap/sveltestrap";
    import api from "../api";
    import { theme } from "../stores/theme.js";

    export let id; // video ID from route param
    let currentTheme = $theme;

    let video = null;
    let error = "";
    let success = "";
    let loading = true;

    let selectedFiles = [];
    let uploading = false;
    let uploadProgress = 0;

    onMount(async () => {
        try {
            const res = await api.get(`/api/videos/${id}`);
            video = res.data;
        } catch (err) {
            error = "Failed to load video information.";
        } finally {
            loading = false;
        }
    });

    function handleFileChange(e) {
        selectedFiles = Array.from(e.target.files);
    }

    async function handleUpload(e) {
        e.preventDefault();
        error = "";
        success = "";

        if (selectedFiles.length === 0) {
            error = "Please select at least one segment file.";
            return;
        }

        const formData = new FormData();
        selectedFiles.forEach((file) => {
            formData.append("segments[]", file);
        });

        uploading = true;

        try {
            await api.post(`/api/videos/${id}/segments`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
                onUploadProgress: (e) => {
                    if (e.lengthComputable) {
                        uploadProgress = Math.round((e.loaded * 100) / e.total);
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
</script>

<Layout>
    <Container class="py-5">
        {#if loading}
            <Spinner color="primary" />
        {:else if error}
            <Alert color="danger">{error}</Alert>
        {:else}
            <Row class="justify-content-center">
                <Col md="10" lg="8">
                    <Card theme={currentTheme === "dark" ? "dark" : "light"}>
                        <CardBody>
                            <h3 class="mb-3">Edit: {video.title}</h3>


                            <!-- Main stitched video -->
                            {#if video.segments?.length > 0}
                                <video
                                    src={`/storage/${video.filename}`}
                                    controls
                                    class="w-100 mb-4"
                                >
                                    <track kind="captions" />
                                </video>
                            {:else}
                                <p class="text-muted mb-4">No stitched video available yet.</p>
                            {/if}

                            <ul class="list-unstyled mb-4">
                                <li><strong>Duration:</strong> {video.duration_readable}</li>
                                <li><strong>Format:</strong> {video.format}</li>
                                <li><strong>Codec:</strong> {video.codec}</li>
                                <li><strong>Size:</strong> {(video.size / 1048576).toFixed(1)} MB</li>
                                <li><strong>Resolution:</strong> {video.width}×{video.height}</li>
                                <li><strong>Recorded At:</strong> {video.recorded_at}</li>
                            </ul>

                            <!-- Upload Form -->
                            <form on:submit={handleUpload}>
                                <FormGroup>
                                    <Label for="segments">Upload Segment Files</Label>
                                    <Input
                                        id="segments"
                                        type="file"
                                        multiple
                                        on:change={handleFileChange}
                                        disabled={uploading}
                                    />
                                </FormGroup>

                                {#if uploading}
                                    <Progress value={uploadProgress} class="mb-3">
                                        {uploadProgress}%
                                    </Progress>
                                {/if}

                                <Button color="primary" disabled={uploading}>
                                    {uploading ? "Uploading..." : "Upload Segments"}
                                </Button>
                            </form>

                            {#if success}
                                <Alert color="success" class="mt-3">{success}</Alert>
                            {/if}
                            {#if error && !loading}
                                <Alert color="danger" class="mt-3">{error}</Alert>
                            {/if}

                            <!-- Existing Segments Table -->
                            {#if video.segments?.length}
                                <h5 class="mt-5">📂 Existing Segments</h5>
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
                                <p class="text-muted mt-4">
                                    No segments uploaded yet.
                                </p>
                            {/if}
                        </CardBody>
                    </Card>
                </Col>
            </Row>
        {/if}
    </Container>
</Layout>
