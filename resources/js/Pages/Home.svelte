<script>
  import Layout from './+layout.svelte';
  import { auth } from '../stores/auth.js';
  import {
    Navbar,
    NavbarBrand,
    Nav,
    NavItem,
    NavLink,
    Container,
    Row,
    Col,
    Alert,
    Button
  } from '@sveltestrap/sveltestrap';

  $: user = $auth.user;

</script>


<Layout>
<!-- CENTERED WELCOME MESSAGE -->
<Container class="text-center">
  <Row class="justify-content-center">
    <Col md="8" lg="6">
      {#if user}
        <Alert class="alert-success">
          <h4 class="alert-heading">Welcome back, {user.name}!</h4>
          <p>We're glad to see you again.</p>
        </Alert>
      {:else}
        <Alert class="alert-info">
          <h4 class="alert-heading">Welcome, guest!</h4>
        </Alert>
      {/if}
    </Col>
  </Row>
</Container>

</Layout>
