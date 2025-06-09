import axios from 'axios';
import { clearAuth } from './stores/auth';

const api = axios.create({
  baseURL: 'http://localhost/', // Or your backend URL
  withCredentials: true, // Crucial for sending cookies!
});

// Handle 401 errors (unauthorized)
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      clearAuth(); // Clear local auth state
    }
    return Promise.reject(error);
  }
);

export default api;
