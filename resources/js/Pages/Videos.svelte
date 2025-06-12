<script>
    import Layout from "./+layout.svelte";
    import { onMount } from "svelte";
    import { Table, Container, Spinner } from "@sveltestrap/sveltestrap";
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
            <p style="font-size:100px">&#127909;</p>
             Your Videos
        </h2>

        {#if loading}
            <Spinner color="primary" />
        {:else if error}
            <p class="text-danger">{error}</p>
        {:else if videos.length === 0}
            <p>No videos found.</p>
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
