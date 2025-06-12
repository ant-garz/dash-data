<script>
    import Layout from "./+layout.svelte";
    import { tick } from "svelte";
    import {
        Container,
        Row,
        Col,
        Card,
        CardBody,
        CardTitle,
        Alert,
        FormGroup,
        Label,
        Input,
        Button,
    } from "@sveltestrap/sveltestrap";
    import { register } from "../auth.js"; // your auth helper

    import { theme } from "../stores/theme.js";

    $: currentTheme = $theme;
    let name = "";
    let email = "";
    let password = "";
    let password_confirmation = "";

    let success = false;
    let error = "";
    let loading = false;
    let countdown = 5;
    let countdownInterval;

    $: formIsValid =
        name.trim().length > 0 &&
        email.trim().length > 0 &&
        password.trim().length > 0 &&
        password_confirmation.trim().length > 0 &&
        password === password_confirmation;

    async function submit(event) {
        event.preventDefault();
        if (!formIsValid) return;

        error = "";
        success = false;
        loading = true;

        try {
            await register({ name, email, password, password_confirmation });

            success = true;
            // Update countdown and redirect after delay
            await tick();

            countdownInterval = setInterval(() => {
                countdown -= 1;
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "/";
                }
            }, 1000);
        } catch (err) {
            error =
                err.response?.data?.message ||
                err.message ||
                "Registration failed";
        } finally {
            loading = false;
        }
    }
</script>

<Layout>
    <Container
        class="d-flex align-items-center justify-content-center min-vh-100"
    >
        <Row class="w-100 justify-content-center">
            <Col sm="12" md="6" lg="4">
                <Card theme={currentTheme === "dark" ? "dark" : "light"}>
                    <CardBody>
                        <CardTitle tag="h4" class="mb-4 text-center"
                            >Registration</CardTitle
                        >

                        {#if error}
                            <Alert color="danger">{error}</Alert>
                        {/if}

                        {#if success}
                            <Alert color="success">
                                Registration successful! Redirecting in {countdown}...
                            </Alert>
                        {/if}

                        <form on:submit={submit}>
                            <FormGroup>
                                <Label for="name">Name</Label>
                                <Input id="name" bind:value={name} required />
                            </FormGroup>
                            <FormGroup>
                                <Label for="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    bind:value={email}
                                    required
                                />
                            </FormGroup>
                            <FormGroup>
                                <Label for="password">Password</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    bind:value={password}
                                    required
                                />
                            </FormGroup>
                            <FormGroup>
                                <Label for="password_confirmation"
                                    >Password Confirmation</Label
                                >
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    bind:value={password_confirmation}
                                    required
                                />
                            </FormGroup>

                            <Button
                                color="primary"
                                class="w-100 mt-3"
                                type="submit"
                                disabled={loading || !formIsValid || success}
                            >
                                {#if loading}
                                    Registering...
                                {:else}
                                    Submit
                                {/if}
                            </Button>
                        </form>
                        <p>Returning user? Please <a href="/login">log in</a></p>
                    </CardBody>
                </Card>
            </Col>
        </Row>
    </Container>
</Layout>
