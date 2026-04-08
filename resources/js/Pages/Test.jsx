import React from 'react';

export default function Test({ auth }) {
    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <div className="bg-white p-8 rounded-lg shadow-md max-w-sm w-full text-center">
                <h1 className="text-2xl font-bold text-blue-600 mb-4">React is Working! 🎉</h1>
                <p className="text-gray-600 mb-6">
                    This is your first Inertia.js React component running inside Laravel.
                </p>
                {auth?.user ? (
                    <p className="text-green-600 font-semibold">Welcome, {auth.user.name}</p>
                ) : (
                    <p className="text-orange-500 font-semibold">You are currently logged out.</p>
                )}
            </div>
        </div>
    );
}
