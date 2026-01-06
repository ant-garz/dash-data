<script>
    import Layout from "./+layout.svelte";
    import { onMount } from "svelte";
    import {
        Table,
        Container,
        Spinner,
        Button,
        Row,
        Col,
    } from "@sveltestrap/sveltestrap";
    import api from "../api";

    let videos = [];
    let loading = true;
    let error = "";

    onMount(async () => {
        try {
            const response = await api.get("/api/videos");
            videos = response.data;
        } catch (e) {
            error = "Failed to load videos.";
        } finally {
            loading = false;
        }
    });

    function openVideo(id) {
        window.location.href = `/videos/${id}`;
    }
</script>

<Layout>
    <Container class="py-5">
        <h2 class="mb-4">
            Your Videos
        </h2>
        <Button
            color="success"
            class="my-3"
            on:click={() => (window.location.href = "/videos/create")}
        >
            +
        </Button>

        {#if loading}
            <Row>
                <Col>
                    <Spinner color="primary" />
                </Col>
            </Row>
        {:else if error}
            <Row>
                <Col>
                    <p class="text-danger">{error}</p>
                </Col>
            </Row>
        {:else if videos.length === 0}
            <Row>
                <Col>
                    <p>No videos found.</p>
                </Col>
            </Row>
        {:else}
            <Table hover responsive>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Duration</th>
                        <th>Format</th>
                        <th>Size</th>
                        <th>Recorded At</th>
                    </tr>
                </thead>
                <tbody>
                    {#each videos as video}
                        <tr
                            on:click={() => openVideo(video.id)}
                            style="cursor: pointer;"
                        >
                            <td>{video.title}</td>
                            <td>{video.duration}s</td>
                            <td>{video.format}</td>
                            <td>{(video.size / 1048576).toFixed(1)} MB</td>
                            <td>{video.recorded_at}</td>
                        </tr>
                    {/each}
                </tbody>
            </Table>
        {/if}
    </Container>
</Layout>
