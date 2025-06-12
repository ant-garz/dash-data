<script>
    import { onMount, onDestroy } from "svelte";
    import { auth } from "../stores/auth.js";
    import { path, updatePath } from "../stores/path.js";
    import { fetchUser, logout } from "../auth";
    import { theme } from "../stores/theme.js";
    import { Moon, Sun } from "svelte-bootstrap-icons";

    import {
        Navbar,
        NavbarBrand,
        Nav,
        NavItem,
        NavLink,
        Button,
        NavbarToggler,
        Collapse,
        Dropdown,
        DropdownItem,
        DropdownToggle,
        DropdownMenu,
    } from "@sveltestrap/sveltestrap";

    let isOpen = false;
    let isProfileDropDownOpen = false;
    let screenWidth = 0;
    $: isMobile = screenWidth < 768;

    $: $auth;
    $: $path;

    let currentTheme;

    // Reactively update body class on theme change
    $: {
        if (typeof window !== "undefined") {
            document.body.classList.remove("light", "dark");
            document.body.classList.add($theme);
        }
    }
    function toggleTheme() {
        theme.update((t) => (t === "light" ? "dark" : "light"));
    }

    function updateScreenWidth() {
        screenWidth = window.innerWidth;
    }

    onMount(() => {
        const hasLoggedIn = localStorage.getItem("hasLoggedIn") === "true";

        // Only fetch user if we know they’ve logged in before and $auth.user is null
        if (hasLoggedIn && !$auth?.user) {
            fetchUser();
        }
        updatePath();

        updateScreenWidth();
        window.addEventListener("resize", updateScreenWidth);
        window.addEventListener("popstate", updatePath);
    });

    onDestroy(() => {
        window.removeEventListener("resize", updateScreenWidth);
        window.removeEventListener("popstate", updatePath);
    });

    async function handleLogout() {
        await logout();
        isOpen = false;
    }

    function isActive(href) {
        return $path === href;
    }
</script>

<Navbar color="dark" dark expand="md" class="mb-4 px-3">
    <NavbarBrand href="/" class="me-auto">Dash data</NavbarBrand>

    {#if isMobile}
        <NavbarToggler on:click={() => (isOpen = !isOpen)} />
        <Collapse {isOpen} navbar>
            <Nav navbar class="flex-column">
                {#if $auth.user}
                    <NavItem>
                        <Dropdown
                            nav
                            inNavbar
                            isOpen={isProfileDropDownOpen}
                            toggle={() =>
                                (isProfileDropDownOpen =
                                    !isProfileDropDownOpen)}
                        >
                            <DropdownToggle nav caret>
                                {$auth?.user.name}
                            </DropdownToggle>
                            <DropdownMenu end>
                                <DropdownItem href="/profile"
                                    >Profile</DropdownItem
                                >
                                <DropdownItem href="/videos"
                                    >Videos</DropdownItem
                                >
                                <DropdownItem divider />
                                <DropdownItem on:click={logout}
                                    >Logout</DropdownItem
                                >
                            </DropdownMenu>
                        </Dropdown>
                    </NavItem>
                    <NavItem>
                        <Button color="outline-light" on:click={handleLogout}
                            >Logout</Button
                        >
                    </NavItem>
                {:else}
                    {#if $path !== "/login"}
                        <NavItem>
                            <NavLink href="/login" active={isActive("/login")}
                                >Login</NavLink
                            >
                        </NavItem>
                    {/if}
                    {#if $path !== "/register"}
                        <NavItem>
                            <NavLink
                                href="/register"
                                active={isActive("/register")}>Register</NavLink
                            >
                        </NavItem>
                    {/if}
                {/if}
                <button
                    on:click={toggleTheme}
                    aria-label="Toggle theme"
                    class="btn btn-link text-white"
                >
                    {#if currentTheme === "light"}
                        <Moon size="24" />
                    {:else}
                        <Sun size="24" />
                    {/if}
                </button>
            </Nav>
        </Collapse>
    {:else}
        <Nav class="ms-auto d-flex align-items-center" navbar>
            {#if $auth.user}
                <NavItem class="me-3">
                    <Dropdown
                        nav
                        inNavbar
                        isOpen={isProfileDropDownOpen}
                        toggle={() =>
                            (isProfileDropDownOpen = !isProfileDropDownOpen)}
                    >
                        <DropdownToggle nav caret>
                            {$auth?.user.name}
                        </DropdownToggle>
                        <DropdownMenu end>
                            <DropdownItem href="/profile">Profile</DropdownItem>
                            <DropdownItem href="/videos">Videos</DropdownItem>
                            <DropdownItem divider />
                            <DropdownItem on:click={logout}>Logout</DropdownItem
                            >
                        </DropdownMenu>
                    </Dropdown>
                </NavItem>
            {:else}
                {#if $path !== "/login"}
                    <NavItem>
                        <NavLink href="/login" active={isActive("/login")}
                            >Login</NavLink
                        >
                    </NavItem>
                {/if}
                {#if $path !== "/register"}
                    <NavItem>
                        <NavLink href="/register" active={isActive("/register")}
                            >Register</NavLink
                        >
                    </NavItem>
                {/if}
            {/if}
            <Button
                on:click={toggleTheme}
                class="theme-toggle-btn"
                title="Toggle Theme"
                aria-label="Toggle Theme"
                style="background-color: var(--toggle-bg); color: var(--toggle-color); border-radius: 50%;"
            >
                {#if $theme === "light"}
                    <Moon size="1.25rem" />
                {:else}
                    <Sun size="1.25rem" />
                {/if}
            </Button>
        </Nav>
    {/if}
</Navbar>

<slot />
