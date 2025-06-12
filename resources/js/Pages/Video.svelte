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
                    <Card>
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
                                    {video.duration}s
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
