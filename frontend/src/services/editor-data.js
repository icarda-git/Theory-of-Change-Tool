import apiClient from '../utilities/api-client';

export const getImpactAreas = async (id) => apiClient.get(`/getImpactAreas`);

export const getActionAreaOutcomes = async (id) => apiClient.get(`/getActionAreas`);

export const getSdgCollections = async (id) => apiClient.get(`/sdgcollections`);
