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
    import { login } from "../auth.js";

    import { theme } from "../stores/theme.js";

    $: currentTheme = $theme;

    let email = "";
    let password = "";
    let error = "";

    async function handleSubmit(event) {
        event.preventDefault(); // prevent form default submit
        try {
            await login({ email, password });
            error = "";
            window.location.href = "/";
        } catch (e) {
            error = "Invalid login credentials";
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
                            >Login</CardTitle
                        >

                        {#if error}
                            <Alert color="danger">{error}</Alert>
                        {/if}

                        <form on:submit={handleSubmit}>
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
                            <Button
                                color="primary"
                                class="w-100 mt-3"
                                type="submit">Login</Button
                            >
                        </form>
                    </CardBody>
                </Card>
            </Col>
        </Row>
    </Container>
</Layout>
