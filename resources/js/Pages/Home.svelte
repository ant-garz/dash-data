<script>
    import Layout from "./+layout.svelte";
    import { auth } from "../stores/auth.js";
    import {
        Container,
        Row,
        Col,
        Alert,
        Button,
    } from "@sveltestrap/sveltestrap";

    $: user = $auth.user;
</script>

<Layout>
    <Container
        class="d-flex justify-content-center align-items-center text-center"
        style="min-height: 80vh;"
    >
        <Row class="justify-content-center w-100">
            <Col md="10" lg="8">
                <h1 class="mb-4">Dash Data</h1>

                <p class="lead">
                    A tool for managing and processing your <strong
                        >dash cam footage</strong
                    >. Upload, convert, and stitch together video clips for a
                    complete view of your journey.
                </p>

                <hr class="my-4" />

                <ul class="text-start mx-auto" style="max-width: 600px;">
                    <li>Convert video codecs for playback or compression</li>
                    <li>
                        Stitch multiple dash cam clips into a single video
                    </li>
                    <li>Organize and review your footage with ease</li>
                </ul>

                <hr class="my-4" />
                {#if user}
                    <Alert class="alert-success mt-4 p-5">
                        <h4 class="alert-heading">
                            Welcome back, {user.name}!
                        </h4>
                        <p>Jump back in and start processing your videos.</p>
                        <Button color="primary" href="/videos" class="mt-3">
                            Go to My Videos
                        </Button>
                    </Alert>
                {:else}
                    <Alert class="alert-info mt-4 p-5">
                        <h4 class="alert-heading">Welcome, guest!</h4>
                        <p>
                            Sign up or log in to begin uploading and editing
                            your dash cam footage.
                        </p>
                    </Alert>
                {/if}
            </Col>
        </Row>
    </Container>
</Layout>
