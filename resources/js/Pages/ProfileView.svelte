<script>
    import Layout from "./+layout.svelte";
    import {
        Card,
        CardBody,
        CardTitle,
        Button,
        Container,
        Row,
        Col,
    } from "@sveltestrap/sveltestrap";
    import { auth } from "../stores/auth";
    import { theme } from "../stores/theme";

    $: currentTheme = $theme;
    $: user = $auth.user;

    function goToEdit() {
        window.location.href = "/profile/edit";
    }
</script>

<Layout>
    <Container class="py-5">
        <Row class="justify-content-center">
            <Col sm="12" md="8" lg="6">
                <Card theme={currentTheme === "dark" ? "dark" : "light"}>
                    <CardBody>
                        <CardTitle tag="h4" class="mb-4 text-center">
                            Profile
                        </CardTitle>

                        {#if user}
                            <p><strong>Name:</strong> {user.name}</p>
                            <p><strong>Email:</strong> {user.email}</p>
                        {:else}
                            <p>Loading user info...</p>
                        {/if}

                        <Button
                            color="primary"
                            class="w-100 mt-3"
                            on:click={goToEdit}
                        >
                            Edit Profile
                        </Button>
                    </CardBody>
                </Card>
            </Col>
        </Row>
    </Container>
</Layout>
