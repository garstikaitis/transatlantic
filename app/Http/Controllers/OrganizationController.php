<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\LocaleOrganization;
use App\Models\OrganizationProjectKey;

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

    public function generateApiKeyForOrganization() {
        try {
            $this->validateInput(request()->all(), [
                'organizationId' => 'required|integer|exists:organizations,id',
                'projectId' => 'required|integer|exists:projects,id',
            ]);
            $apiKey = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
            OrganizationProjectKey::create([
                'organizationId' => request('organizationId'),
                'projectId' => request('projectId'),
                'key' => $apiKey
            ]);
            return response()->json(['success' => true, 'data' => $apiKey], 201);
        } catch(Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getApiKeyForOrganization() {
        try {
            $this->validateInput(request()->all(), [
                'organizationId' => 'required|integer|exists:organizations,id',
                'projectId' => 'required|integer|exists:projects,id',
            ]);
            $key = OrganizationProjectKey::where('organizationId', request('organizationId'))->where('projectId', request('projectId'))->first();
            return response()->json(['success' => true, 'data' => $key], 200);
        } catch(Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getUserOrganizations() {
        try {
            $organizations = auth()->user()->organizations()->get();
            if(auth()->user()->isSuperAdmin()) {
                $organizations = Organization::all();
            }
            return response()->json(['success' => true, 'data' => $organizations]);
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
                    'subdomain' => 'string|required',
                ]
            );

            $request = request()->all();
            unset($request['logo']);

            $organization = Organization::create($request);

            if(request('logo') !== 'null') {
                $result = request()->file('logo')->storeOnCloudinaryAs('prod/' . $organization->id, 'logo');
                
                $organization->logo = $result->getSecurePath();
                $organization->save();
            }


            return response()->json(['success' => true, 'data' => $organization], 201);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function editOrganization()
    {
        try {
            
            $this->validateInput(
                request()->all(), [
                    'organizationId' => 'required|integer|exists:organizations,id',
                    'name' => 'string|required',
                    'subdomain' => 'string|required',
                ]
            );

            $organization = Organization::findOrFail(request('organizationId'));
            $organization->name = request('name');
            $organization->subdomain = request('subdomain');
            $organization->save();

            if(request('newLogo') !== 'null') {
                $result = request()->file('newLogo')->storeOnCloudinaryAs('prod/' . $organization->id, 'logo');
    
                $organization->logo = $result->getSecurePath();
                $organization->save();
            }

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
