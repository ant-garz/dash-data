<script>
    import Layout from "./+layout.svelte";
    import {
        Card,
        CardBody,
        CardTitle,
        Form,
        FormGroup,
        Label,
        Input,
        Button,
        Alert,
        Container,
        Row,
        Col,
    } from "@sveltestrap/sveltestrap";
    import { auth } from "../stores/auth";
    import { theme } from "../stores/theme";
    import { updateUser } from "../user";

    $: currentTheme = $theme;
    $: user = $auth.user;

    let name = user?.name;
    let email = user?.email;

    console.log(name, email)
    let error = "";
    let success = "";

    async function handleSubmit(e) {
        e.preventDefault();
        error = success = "";

        try {
            await updateUser({ name, email });
            success = "Profile updated successfully.";
        } catch (e) {
            error = "Failed to update profile.";
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
                            Edit Profile
                        </CardTitle>

                        {#if error}
                            <Alert color="danger">{error}</Alert>
                        {/if}
                        {#if success}
                            <Alert color="success">{success}</Alert>
                        {/if}

                        <form on:submit={handleSubmit}>
                            <FormGroup>
                                <Label for="name">Name</Label>
                                <Input id="name" bind:value={name} required />
                            </FormGroup>

                            <FormGroup>
                                <Label for="email">Email</Label>
                                <Input id="email" type="email" bind:value={email} required />
                            </FormGroup>

                            <Button color="primary" class="w-100 mt-3" type="submit">
                                Save Changes
                            </Button>
                        </form>

                        <Button color="primary" class="w-100 mt-5" on:click={() => window.location.href="/profile/edit/password"}>
                            Change password
                        </Button>
                    </CardBody>
                </Card>
            </Col>
        </Row>
    </Container>
</Layout>
