// stores/path.js
import { writable } from 'svelte/store';

export const path = writable(window.location.pathname);

export function updatePath() {
  path.set(window.location.pathname);
}
