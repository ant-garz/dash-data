import { writable } from 'svelte/store';

export const auth = writable({
  user: null,
});

export function clearAuth() {
  auth.set({ user: null });
}