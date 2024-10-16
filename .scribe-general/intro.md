# Introduction

The API for basic application that will be used

<aside>
    <strong>Base URL</strong>: <code>http://127.0.0.1:8000</code>
</aside>

This documentation aims to provide all the information you need to work with our API.

<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
[HINT] these are the responses and their purposes probabely

* 200 OK: The request has been successfully processed, and the response contains the requested data or operation results.

* 201 Created: The request has been successfully processed, and a new resource has been created as a result.

* 204 No Content: The request has been successfully processed, but there is no content to return in the response payload (often used for DELETE requests).

* 400 Bad Request: The server cannot understand or process the request due to malformed syntax, invalid parameters, or other client-side errors.

* 401 Unauthorized: The request requires authentication, and the client must provide valid credentials (e.g., username and password) to access the requested resource.

* 403 Forbidden: The server understands the request and the client is authenticated, but the client does not have sufficient permissions to access the requested resource.

* 404 Not Found: The server cannot find the requested resource or endpoint.

* 405 Method Not Allowed: The requested HTTP method is not allowed for the given resource or endpoint.

* 422 Unprocessable Entity: The server understands the request, but the provided data or input fails the validation rules or is semantically incorrect.

* 500 Internal Server Error: An unexpected error occurred on the server, indicating a problem with the server's configuration or implementation.

* 502 Bad Gateway: The server acting as a gateway or proxy received an invalid response from an upstream server.

* 503 Service Unavailable: The server is temporarily unable to handle the request due to maintenance, overloading, or other server-side issues.

* 504 Gateway Timeout: The server acting as a gateway or proxy did not receive a timely response from an upstream server.

