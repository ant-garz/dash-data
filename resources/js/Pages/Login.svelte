<script>
  import { Card, CardBody, CardTitle, Form, FormGroup, Label, Input, Button, Alert, Container, Row, Col } from '@sveltestrap/sveltestrap';
  import { login } from '../auth.js';
  let email = '';
  let password = '';
  let error = '';

  async function handleSubmit() {
  try {
    await login(email, password);
    error = '';
  } catch (e) {
    error = 'Invalid login credentials';
  }
}
</script>

<Container class="d-flex align-items-center justify-content-center min-vh-100">
  <Row class="w-100 justify-content-center">
    <Col sm="12" md="6" lg="4">
      <Card>
        <CardBody>
          <CardTitle tag="h4" class="mb-4 text-center">Login</CardTitle>

          {#if error}
            <Alert color="danger">{error}</Alert>
          {/if}

          <Form on:submit={e => { e.preventDefault(); handleSubmit(); }}>
            <FormGroup>
              <Label for="email">Email</Label>
              <Input id="email" type="email" bind:value={email} required />
            </FormGroup>
            <FormGroup>
              <Label for="password">Password</Label>
              <Input id="password" type="password" bind:value={password} required />
            </FormGroup>
            <Button color="primary" class="w-100 mt-3">Login</Button>
          </Form>
        </CardBody>
      </Card>
    </Col>
  </Row>
</Container>
