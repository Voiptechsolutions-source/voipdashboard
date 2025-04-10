@extends('layouts.app')

@section('content')
  <div class="container">
    <h2>User Management</h2>
    <button class="btn btn-primary mb-3" id="addUserBtn">Add User</button>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role->name ?? 'No Role' }}</td>
            <td>
              <button class="btn btn-success editUserBtn" data-id="{{ $user->id }}">Edit</button>
              <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Add User Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserForm">
            @csrf
            <div class="mb-3">
              <label>Name:</label>
              <input type="text" name="name" id="addName" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password:</label>
              <input type="password" name="password" id="addpassword" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Confirm Password</label>
              <input type="password" name="password_confirmation" id="addPasswordConfirmation" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Email:</label>
              <input type="email" name="email" id="addEmail" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Role:</label>
              <select name="role_id" id="addRole" class="form-control"></select>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editUserForm">
            @csrf
            <input type="hidden" id="userId">
            <div class="mb-3">
              <label>Name:</label>
              <input type="text" id="editName" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Email:</label>
              <input type="email" id="editEmail" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="password">New Password (Leave blank to keep current):</label>
              <input type="password" name="password" id="password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="password_confirmation">Confirm New Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            <div class="mb-3">
              <label>Role:</label>
              <select id="editRole" class="form-control"></select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function () {
      // Set up AJAX headers for CSRF protection
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      /** ========================== ADD USER ========================== **/

      // Open "Add User" Modal & Fetch Roles
      $('#addUserBtn').on('click', function () {
        fetchRoles('#addRole').then(() => {
          $('#addUserModal').modal('show');
        });
      });

      // Handle Add User Form Submission
      $('#addUserForm').on('submit', function (e) {
        e.preventDefault();
        let formData = {
          name: $('#addName').val(),
          email: $('#addEmail').val(),
          role_id: $('#addRole').val(),
          password: $('#addpassword').val(),
          password_confirmation: $('#addPasswordConfirmation').val()
        };
        $.post('/users/store', formData, function (response) {
          if (response.success) {
            alert('User added successfully!');
            $('#addUserModal').modal('hide');
            location.reload();
          } else {
            alert(response.message || 'Error adding user.');
          }
        }).fail(handleAjaxError);
      });

      /** ========================== EDIT USER ========================== **/

      // Open "Edit User" Modal & Fetch User Data
      $(document).on('click', '.editUserBtn', function () {
        let userId = $(this).data('id');
        $.get('/users/edit/' + userId, function (response) {
          if (response.success) {
            $('#userId').val(response.user.id);
            $('#editName').val(response.user.username);
            $('#editEmail').val(response.user.email);
            fetchRoles('#editRole', response.user.role_id).then(() => {
              $('#editUserModal').modal('show');
            });
          } else {
            alert(response.message || 'Error fetching user data!');
          }
        }).fail(handleAjaxError);
      });

      // Handle Edit User Form Submission
      $('#editUserForm').on('submit', function (e) {
        e.preventDefault();
        let userId = $('#userId').val();
        let formData = {
          name: $('#editName').val(),
          email: $('#editEmail').val(),
          password: $('#password').val(),
          password_confirmation: $('#password_confirmation').val(),
          role_id: $('#editRole').val()
        };
        $.post('/users/update/' + userId, formData, function (response) {
          if (response.success) {
            alert('User updated successfully!');
            $('#editUserModal').modal('hide');
            location.reload();
          } else {
            alert(response.message || 'Error updating user.');
          }
        }).fail(handleAjaxError);
      });

      /** ========================== FETCH ROLES ========================== **/

      function fetchRoles(selector, selectedRoleId = null) {
        return new Promise((resolve, reject) => {
          console.log('Fetching roles for selector:', selector, 'Selected Role ID:', selectedRoleId);
          $.get('/roles/list', function (response) {  // Updated to /roles/list
            console.log('Roles response:', response);
            if (response.success && response.roles) {
              let roleOptions = '<option value="">Select Role</option>';
              response.roles.forEach(role => {
                let selected = selectedRoleId == role.id ? 'selected' : '';
                roleOptions += `<option value="${role.id}" ${selected}>${role.name}</option>`;
              });
              $(selector).html(roleOptions);
              console.log('Dropdown updated with:', roleOptions);
              resolve();
            } else {
              alert('Error fetching roles: ' + (response.message || 'No roles found'));
              reject();
            }
          }).fail(function (xhr) {
            console.error('AJAX Error:', xhr);
            handleAjaxError(xhr);
            reject(xhr);
          });
        });
      }

      /** ========================== HANDLE AJAX ERRORS ========================== **/

      function handleAjaxError(xhr) {
        let errorMessage = 'An error occurred!';
        if (xhr.responseJSON && xhr.responseJSON.errors) {
          errorMessage = 'Please fix the following errors:\n';
          $.each(xhr.responseJSON.errors, function (key, value) {
            errorMessage += value + '\n';
          });
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        alert(errorMessage);
      }
    });
  </script>
@endsection