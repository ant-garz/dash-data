import api from './api';
import { fetchUser } from "./auth"
/**
 * Update user profile (name, email, etc.)
 * @param {Object} data - { name: string, email: string }
 */
export async function updateUser(data) {
	try {
		const response = await api.put('/api/user', data);

		await fetchUser();
		return response.data;
	} catch (error) {
		handleApiError(error);
	}
}

/**
 * Update user password
 * @param {Object} data - { current_password, new_password, new_password_confirmation }
 */
export async function updatePassword(data) {
	try {
		const response = await api.put('/api/user/password', data);
		return response.data;
	} catch (error) {
		handleApiError(error);
	}
}

/**
 * Basic error handler
 */
function handleApiError(error) {
	if (error.response?.data?.message) {
		throw new Error(error.response.data.message);
	}
	throw new Error('An unknown error occurred.');
}