import api from './api';
import { auth, clearAuth } from "./stores/auth"

export async function fetchUser() {
  try {
    const response = await api.get('/user');
    auth.set({ user: response.data });
  } catch (error) {
    clearAuth();
  }
}

export async function login({ email, password }) {
  await api.get('sanctum/csrf-cookie');
  await api.post('login', { email, password });
  await fetchUser();
}

export async function register({ name, email, password, password_confirmation }) {
  await api.get('sanctum/csrf-cookie');
  await api.post('register', { name, email, password, password_confirmation });
  await fetchUser();
}

export async function logout() {
  try {
    await api.post('logout');
  } catch (error) {
    //
  } finally {
    clearAuth();
    window.location.href = '/login';
  }
}