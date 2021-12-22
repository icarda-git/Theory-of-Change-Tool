import apiClient from '../utilities/api-client';

export const logout = async () => apiClient.post('/logout');

export const verify = async (code) => apiClient.post('/handleMelCode', { code });
