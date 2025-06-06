import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost'; // adjust if needed

export async function login(email, password) {
  await axios.get('/sanctum/csrf-cookie');
  const response = await axios.post('/api/login', { email, password });
  return response.data;
}

export async function logout() {
  await axios.post('/api/logout');
}

export async function getUser() {
  const response = await axios.get('/api/user');
  return response.data;
}
