<?php

namespace App\Http\Controllers;

/**
 * --------------------------------------------------------------------------
 * Base Controller
 * --------------------------------------------------------------------------
 *
 * This is the abstract base class for all controllers in your Laravel app.
 * By default, it doesn’t contain any logic, but every controller you create
 * (e.g. `SampleInquiryController`, `UserController`, etc.) will extend this
 * class.
 *
 * Why abstract?
 * - Since this class is not meant to be instantiated directly, it is declared
 *   as `abstract`. You only ever extend it.
 *
 * Typical usage:
 * - You can place common methods here that you want to share across
 *   multiple controllers.
 * - Laravel’s traits (like `AuthorizesRequests`, `DispatchesJobs`,
 *   `ValidatesRequests`) are often pulled into this class for convenience.
 * - Example: if you add a `successResponse()` helper here, all your
 *   controllers can use it.
 * - This keeps your code DRY (Don’t Repeat Yourself) and organized.
 */
abstract class Controller
{
    //
}
