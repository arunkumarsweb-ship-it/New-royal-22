// Authentication Management System

// Initialize default admin credentials
function initializeAdminCredentials() {
    if (!localStorage.getItem('admin_username')) {
        localStorage.setItem('admin_username', 'idhaya123');
    }
    if (!localStorage.getItem('admin_password')) {
        // Default password: ROYALsarees@92
        localStorage.setItem('admin_password', 'ROYALsarees@92');
    }
}

// Check if user is logged in
function isAdminLoggedIn() {
    return sessionStorage.getItem('admin_logged_in') === 'true';
}

// Login function
function adminLogin(username, password) {
    const storedUsername = localStorage.getItem('admin_username');
    const storedPassword = localStorage.getItem('admin_password');
    
    if (username === storedUsername && password === storedPassword) {
        sessionStorage.setItem('admin_logged_in', 'true');
        sessionStorage.setItem('admin_username', username);
        return { success: true };
    }
    return { success: false, error: 'Invalid username or password' };
}

// Logout function
function adminLogout() {
    sessionStorage.removeItem('admin_logged_in');
    sessionStorage.removeItem('admin_username');
}

// Change password
function changeAdminPassword(oldPassword, newPassword) {
    const storedPassword = localStorage.getItem('admin_password');
    
    if (oldPassword !== storedPassword) {
        return { success: false, error: 'Current password is incorrect' };
    }
    
    if (!newPassword || newPassword.length < 4) {
        return { success: false, error: 'New password must be at least 4 characters' };
    }
    
    localStorage.setItem('admin_password', newPassword);
    return { success: true };
}

// Change username
function changeAdminUsername(newUsername) {
    if (!newUsername || newUsername.trim().length < 3) {
        return { success: false, error: 'Username must be at least 3 characters' };
    }
    
    localStorage.setItem('admin_username', newUsername.trim());
    return { success: true };
}

// Initialize on load
initializeAdminCredentials();

