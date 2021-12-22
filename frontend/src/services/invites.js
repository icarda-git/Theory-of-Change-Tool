import apiClient from '../utilities/api-client';

export const getUserInvites = async () => apiClient.get(`/invites`);
export const acceptInvite = async (id) => apiClient.post(`/invites/${id}/accept`);
export const rejectInvite = async (id) => apiClient.post(`/invites/${id}/reject`);
