<?php

namespace App\Traits;

trait AlertTrait
{
    /**
     * Handle validation errors and return JSON response
     */
    protected function validationError($errors)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'errors' => $errors->all(),
                'message' => 'يرجى إصلاح الأخطاء التالية'
            ], 422);
        }

        return redirect()->back()
            ->withErrors($errors)
            ->withInput()
            ->with('error', 'يرجى إصلاح الأخطاء المطلوبة');
    }

    /**
     * Handle success messages
     */
    protected function successMessage($message, $route = null, $routeParams = [])
    {
        if (request()->ajax()) {
            $response = [
                'success' => true,
                'message' => $message
            ];

            if ($route) {
                $response['redirect'] = route($route, $routeParams);
            }

            return response()->json($response);
        }

        $redirect = $route ? redirect()->route($route, $routeParams) : redirect()->back();
        
        return $redirect->with('success', $message);
    }

    /**
     * Handle error messages
     */
    protected function errorMessage($message, $route = null, $routeParams = [])
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        $redirect = $route ? redirect()->route($route, $routeParams) : redirect()->back();
        
        return $redirect->with('error', $message);
    }

    /**
     * Handle warning messages
     */
    protected function warningMessage($message, $route = null, $routeParams = [])
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'type' => 'warning'
            ], 200);
        }

        $redirect = $route ? redirect()->route($route, $routeParams) : redirect()->back();
        
        return $redirect->with('warning', $message);
    }

    /**
     * Handle info messages
     */
    protected function infoMessage($message, $route = null, $routeParams = [])
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'type' => 'info'
            ]);
        }

        $redirect = $route ? redirect()->route($route, $routeParams) : redirect()->back();
        
        return $redirect->with('info', $message);
    }

    /**
     * Handle image upload with error handling
     */
    protected function handleImageUpload($file, $model = null, $directory = 'uploads')
    {
        try {
            if (!$file || !$file->isValid()) {
                return [
                    'success' => false,
                    'message' => 'الملف المرفوع غير صالح'
                ];
            }

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path($directory);
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old image if model is provided and has existing image
            if ($model && isset($model->service_img) && $model->service_img) {
                $oldImagePath = public_path($directory . '/' . $model->service_img);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Move uploaded file
            $file->move($uploadPath, $filename);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $directory . '/' . $filename
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'فشل في رفع الصورة: ' . $e->getMessage()
            ];
        }
    }
}