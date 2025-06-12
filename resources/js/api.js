import axios from 'axios';
import { clearAuth } from './stores/auth';

const api = axios.create({
  baseURL: 'http://localhost', // Or your backend URL
  withCredentials: true, // Crucial for sending cookies!
});

export default api;
