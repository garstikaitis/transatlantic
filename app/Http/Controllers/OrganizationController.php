<?php

namespace App\Http\Controllers;

use App\Models\LocaleOrganization;
use App\Models\Organization;
use App\Models\OrganizationUser;
use Exception;

class OrganizationController extends Controller
{
    public function getAllOrganizations()
    {
        try {
            return response()->json(['success' => true, 'data' => Organization::all()]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching locales'], 500);
        }
    }

    public function getOrganizationById(int $id) {

        $organization = Organization::findOrFail($id);

        try {
            return response()->json(['success' => true, 'data' => $organization->load('locales')]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching organization'], 500);
        }

    }

    public function createOrganization()
    {
        try {
            
            $this->validateInput(
                request()->all(), [
                    'name' => 'string|required',
                    'subdomain' => 'string|required'
                ]
            );

            $organization = Organization::create(request()->all());

            return response()->json(['success' => true, 'data' => $organization], 201);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addUserToOrganization() {

         try {

            $this->validateInput(
                request()->all(), [
                    'userId' => 'integer|required|exists:users,id',
                    'organizationId' => 'integer|required|exists:organizations,id'
                ]
            );

            $map = OrganizationUser::create(request()->all());

            return response()->json(['success' => true, 'data' => $map], 201);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }

    }

    public function removeUserFromOrganization() {

         try {

            $this->validateInput(
                request()->all(), [
                    'userId' => 'integer|required|exists:users,id',
                    'organizationId' => 'integer|required|exists:organizations,id'
                ]
            );

            $map = OrganizationUser::where('userId', request('userId'))->where('organizationId', request('organizationId'))->firstOrFail();

            $map->delete();

            return response()->json(['success' => true, 'data' => []], 200);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => 'Error removing user from organization'], 500);

        }

    }

    public function addLocaleToOrganization() {

        try {

            $this->validateInput(
                request()->all(), 
                [
                    'localeId' => 'integer|required|exists:locales,id',
                    'organizationId' => 'integer|required|exists:organizations,id'
                ]
            );

            $map = LocaleOrganization::create(request()->all());

            return response()->json(['success' => true, 'data' => $map], 201);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => 'Error adding locale to organization'], 500);

        }

    }

    public function removeLocaleFromOrganization() {

        try {

            $this->validateInput(
                request()->all(), 
                [
                    'localeId' => 'integer|required|exists:locales,id',
                    'organizationId' => 'integer|required|exists:organizations,id'
                ]
            );

            $map = LocaleOrganization::where('organizationId', request('organizationId'))->where('localeId', request('localeId'));

            $map->delete();

            return response()->json(['success' => true, 'data' => []], 200);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => 'Error removing locale from organization'], 500);

        }

    }
}
