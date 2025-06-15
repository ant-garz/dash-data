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
        Button
    } from "@sveltestrap/sveltestrap";
    import api from "../api";

    import { theme } from "../stores/theme.js";

    $: currentTheme = $theme;

    let video = null;
    let loading = true;
    let error = "";
    export let id; // prop from laravel

    onMount(async () => {
        try {
            const response = await api.get(`/api/videos/${id}`);
            video = response.data;
        } catch (e) {
            error = "Video not found or access denied.";
        } finally {
            loading = false;
        }
    });

    function edit(){
        window.location.href="/videos/" + video.id + "/edit"
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">{video.title}</h3>
                                <Button color="primary" on:click={edit}>Edit</Button>
                            </div>

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
