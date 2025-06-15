<script>
    import Layout from "./+layout.svelte";
    import {
        Card,
        CardBody,
        CardTitle,
        FormGroup,
        Label,
        Input,
        Button,
        Alert,
        Container,
        Row,
        Col,
        FormFeedback
    } from "@sveltestrap/sveltestrap";
    import { theme } from "../stores/theme";
    import api from "../api";

    let currentTheme = $theme;

    let title = "";
    let description = "";
    let error = "";
    let success = "";
    let loading = false;

    let titleTouched = false;
    let descriptionTouched = false;

    function isValid(field) {
        return field.trim().length > 0;
    }

    async function handleSubmit(e) {
        e.preventDefault();
        error = "";
        success = "";
        titleTouched = true;
        descriptionTouched = true;

        if (!isValid(title) || !isValid(description)) {
            error = "Please fill in both title and description.";
            return;
        }

        loading = true;

        try {
            const res = await api.post("/api/videos", { title, description });
            success = "Video created! Redirecting to upload...";
            const videoId = res.data.id;
            window.location.href = `/videos/${videoId}/edit`;
        } catch (err) {
            error = "Failed to create video. Please try again.";
        } finally {
            loading = false;
        }
    }
</script>

<Layout>
    <Container class="py-5">
        <Row class="justify-content-center">
            <Col sm="12" md="8" lg="6">
                <Card theme={currentTheme === "dark" ? "dark" : "light"}>
                    <CardBody>
                        <CardTitle tag="h4" class="mb-4 text-center">
                            Create New Video
                        </CardTitle>

                        {#if error}
                            <Alert color="danger">{error}</Alert>
                        {/if}
                        {#if success}
                            <Alert color="success">{success}</Alert>
                        {/if}

                        <form on:submit={handleSubmit}>
                            <FormGroup>
                                <Label for="title">Video Title</Label>
                                <Input
                                    id="title"
                                    type="text"
                                    bind:value={title}
                                    placeholder="Enter video title"
                                    on:blur={() => (titleTouched = true)}
                                    invalid={titleTouched && !isValid(title)}
                                    disabled={loading}
                                    required
                                />
                                <FormFeedback>Title is required.</FormFeedback>
                            </FormGroup>

                            <FormGroup>
                                <Label for="description">Description</Label>
                                <Input
                                    id="description"
                                    type="textarea"
                                    bind:value={description}
                                    placeholder="Add a short description of this video"
                                    rows="4"
                                    on:blur={() => (descriptionTouched = true)}
                                    invalid={descriptionTouched && !isValid(description)}
                                    disabled={loading}
                                    required
                                />
                                <FormFeedback>Description is required.</FormFeedback>
                            </FormGroup>

                            <Button
                                color="primary"
                                class="w-100 mt-3"
                                type="submit"
                                disabled={loading}
                            >
                                {loading ? "Creating..." : "Create Video"}
                            </Button>
                        </form>
                    </CardBody>
                </Card>
            </Col>
        </Row>
    </Container>
</Layout>
