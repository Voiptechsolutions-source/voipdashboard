@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <h2 class="lead">Roles Management</h2>

        <!-- Add Role Button -->
        <div class="mb-3">
          <a href="{{ route('roles.create') }}" class="btn btn-success lead">
            + Add New Role
          </a>
        </div>

        <!-- Roles Table -->
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="lead">Role Name</th>
                  <th class="lead">Permissions</th>
                  <th class="lead">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($roles as $role)
                  <tr>
                    <td class="lead">{{ $role->name }}</td>
                    <td class="lead">
                      @foreach($role->permissions as $permission)
                        <span class="badge bg-primary">
                          {{ $permission->page_name }}
                          ({{ $permission->pivot->can_view ? 'View' : '' }}
                          {{ $permission->pivot->can_edit ? 'Edit' : '' }}
                          {{ $permission->pivot->can_delete ? 'Delete' : '' }})
                        </span>
                      @endforeach
                      <button class="btn btn-sm btn-info lead managePermissionsBtn"
                              data-id="{{ $role->id }}"
                              data-permissions="{{ json_encode($role->permissions->pluck('id')->toArray()) }}">
                        Manage Permissions
                      </button>
                    </td>
                    <td>
                      <button class="btn btn-sm btn-warning lead editRoleBtn"
                              data-id="{{ $role->id }}"
                              data-name="{{ $role->name }}">
                        Edit
                      </button>
                      <button class="btn btn-sm btn-danger lead deleteRoleBtn"
                              data-id="{{ $role->id }}">
                        Delete
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- Edit Role Modal -->
        <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title lead" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form id="editRoleForm">
                  @csrf
                  <input type="hidden" id="editRoleId" name="role_id">
                  <div class="mb-3">
                    <label class="form-label lead">Role Name</label>
                    <input type="text" class="form-control lead" id="editRoleName" name="name">
                  </div>
                  <button type="submit" class="btn btn-success lead">Save Changes</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Delete Role Modal -->
        <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title lead">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p class="lead">Are you sure you want to delete this role?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary lead" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger lead" id="confirmDeleteRole">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Manage Permissions Modal -->
        <div class="modal fade" id="managePermissionsModal" tabindex="-1" aria-labelledby="managePermissionsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title lead" id="managePermissionsModalLabel">Set Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form id="managePermissionsForm">
                  @csrf
                  <input type="hidden" id="manageRoleId" name="role_id">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="lead">Page Name</th>
                        <th class="lead">View</th>
                        <th class="lead">Edit</th>
                        <th class="lead">Delete</th>
                      </tr>
                    </thead>
                    <tbody id="permissionsTableBody">
                      @foreach($permissions as $permission)
                        <tr>
                          <td class="lead">{{ $permission->page_name }}</td>
                          <td>
                            <input type="checkbox"
                                   id="permission_{{ $permission->id }}_view"
                                   name="permissions[{{ $permission->id }}][can_view]"
                                   value="1">
                          </td>
                          <td>
                            <input type="checkbox"
                                   id="permission_{{ $permission->id }}_edit"
                                   name="permissions[{{ $permission->id }}][can_edit]"
                                   value="1">
                          </td>
                          <td>
                            <input type="checkbox"
                                   id="permission_{{ $permission->id }}_delete"
                                   name="permissions[{{ $permission->id }}][can_delete]"
                                   value="1">
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <button type="submit" class="btn btn-success lead">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript to handle modals -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Edit Role Modal
      document.querySelectorAll(".editRoleBtn").forEach(button => {
        button.addEventListener("click", function () {
          const roleId = this.getAttribute("data-id");
          const roleName = this.getAttribute("data-name");
          document.getElementById("editRoleId").value = roleId;
          document.getElementById("editRoleName").value = roleName;
          new bootstrap.Modal(document.getElementById("editRoleModal")).show();
        });
      });
      document.getElementById("editRoleForm").addEventListener("submit", function (e) {
            e.preventDefault();
            
            let roleId = document.getElementById("editRoleId").value;
            let roleName = document.getElementById("editRoleName").value;
            
            fetch(`/roles/${roleId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ name: roleName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Role updated successfully!");
                    location.reload(); // Refresh page to reflect changes
                } else {
                    alert("Error updating role.");
                }
            })
            .catch(error => console.error('Error:', error));
        });


      // Delete Role Modal
      document.querySelectorAll(".deleteRoleBtn").forEach(button => {
        button.addEventListener("click", function () {
          const roleId = this.getAttribute("data-id");
          document.getElementById("confirmDeleteRole").setAttribute("data-id", roleId);
          new bootstrap.Modal(document.getElementById("deleteRoleModal")).show();
        });
      });

      document.getElementById("confirmDeleteRole").addEventListener("click", function () {
            let roleId = this.getAttribute("data-id");

            fetch(`/roles/${roleId}/delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Role deleted successfully!");
                    location.reload(); // Refresh page
                } else {
                    alert("Error deleting role.");
                }
            })
            .catch(error => console.error('Error:', error));
        });


      // Manage Permissions Modal
      document.querySelectorAll(".managePermissionsBtn").forEach(button => {
        button.addEventListener("click", function () {
          const roleId = this.getAttribute("data-id");
          document.getElementById("manageRoleId").value = roleId;

          fetch(`/roles/${roleId}/permissions`)
            .then(response => {
              if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
              return response.json();
            })
            .then(data => {
              console.log("Fetched Permissions:", data);

              // Reset all checkboxes
              document.querySelectorAll("#permissionsTableBody input[type='checkbox']").forEach(checkbox => {
                checkbox.checked = false;
              });

              // Set checkboxes based on pivot data
              data.forEach(permission => {
                const viewCheckbox = document.querySelector(`#permission_${permission.id}_view`);
                const editCheckbox = document.querySelector(`#permission_${permission.id}_edit`);
                const deleteCheckbox = document.querySelector(`#permission_${permission.id}_delete`);

                if (viewCheckbox && permission.pivot) viewCheckbox.checked = permission.pivot.can_view === 1;
                if (editCheckbox && permission.pivot) editCheckbox.checked = permission.pivot.can_edit === 1;
                if (deleteCheckbox && permission.pivot) deleteCheckbox.checked = permission.pivot.can_delete === 1;
              });

              new bootstrap.Modal(document.getElementById("managePermissionsModal")).show();
            })
            .catch(error => console.error("Error fetching permissions:", error));
        });
      });

      // Handle Permissions Form Submission
      document.getElementById("managePermissionsForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const roleId = document.getElementById("manageRoleId").value;
        const formData = new FormData(this);

        console.log("Role ID:", roleId);

        if (!roleId) {
          alert("Role ID is missing!");
          return;
        }

        console.log("Submitting fetch request...");

        fetch(`/roles/${roleId}/permissions`, {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        })
        .then(response => response.json())
        .then(data => {
          console.log("Response:", data);
          if (data.success) {
            location.reload();
          } else {
            alert('Error updating permissions');
          }
        })
        .catch(error => console.error('Fetch Error:', error));
      });
    });
  </script>
@endsection