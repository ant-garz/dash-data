import api from "./api";
import { auth, clearAuth } from "./stores/auth";

export async function fetchUser() {
    try {
        const response = await api.get("/api/user");
        auth.set({ user: response.data });
    } catch (error) {
        console.error("Fetch user failed:", error?.response?.status);
        clearAuth();
    }
}

export async function login({ email, password }) {

    // Step 2: Post credentials
    const response = await api.post("/login", { email, password });

    // Step 3: Only after a 200, safely fetch user
    if (response.status === 200) {
        localStorage.setItem("hasLoggedIn", "true");
    }
}

export async function register({ name, email, password, password_confirmation }) {

    const response = await api.post("/register", {
        name,
        email,
        password,
        password_confirmation,
    });

    if (response.status === 201) {
        localStorage.setItem("hasLoggedIn", "true");
    }
}

export async function logout() {
    try {
        await api.post("logout");
    } catch (error) {
        //
    } finally {
        clearAuth();
        localStorage.removeItem("hasLoggedIn");
        window.location.href = "/login";
    }
}
