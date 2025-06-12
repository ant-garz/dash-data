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
    import { updatePassword } from "../user";
    import { theme } from "../stores/theme";

    $: currentTheme = $theme;

    let current_password = "";
    let new_password = "";
    let new_password_confirmation = "";
    let error = "";
    let success = "";

    async function handleSubmit(e) {
        e.preventDefault();
        error = success = "";

        if (new_password !== new_password_confirmation) {
            error = "Passwords do not match.";
            return;
        }

        try {
            await updatePassword({
                current_password,
                new_password,
                new_password_confirmation,
            });
            success = "Password updated successfully.";
            current_password = new_password = new_password_confirmation = "";
        } catch (e) {
            if (e?.response?.data?.errors?.current_password) {
                error = e.response.data.errors.current_password[0];
            } else if (e?.response?.data?.errors?.new_password) {
                error = e.response.data.errors.new_password[0];
            } else {
                error = "Failed to update password.";
            }
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
                            Change Password
                        </CardTitle>

                        {#if error}
                            <Alert color="danger">{error}</Alert>
                        {/if}
                        {#if success}
                            <Alert color="success">{success}</Alert>
                        {/if}

                        <form on:submit={handleSubmit}>
                            <FormGroup>
                                <Label for="current_password"
                                    >Current Password</Label
                                >
                                <Input
                                    id="current_password"
                                    type="password"
                                    bind:value={current_password}
                                    required
                                />
                            </FormGroup>

                            <FormGroup>
                                <Label for="new_password">New Password</Label>
                                <Input
                                    id="new_password"
                                    type="password"
                                    bind:value={new_password}
                                    required
                                />
                            </FormGroup>

                            <FormGroup>
                                <Label for="new_password_confirmation"
                                    >Confirm New Password</Label
                                >
                                <Input
                                    id="new_password_confirmation"
                                    type="password"
                                    bind:value={new_password_confirmation}
                                    required
                                />
                            </FormGroup>

                            <Button
                                color="primary"
                                class="w-100 mt-3"
                                type="submit"
                            >
                                Update Password
                            </Button>
                        </form>
                    </CardBody>
                </Card>
            </Col>
        </Row>
    </Container>
</Layout>
